<?php

namespace App\Repositories;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Models\Cart;
use App\Models\DealOfTheDay;
use App\Models\FlashDealProduct;
use App\Models\Product;
use App\Models\Tag;
use App\Models\Translation;
use App\Models\Wishlist;
use App\Traits\ProductTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductRepository implements ProductRepositoryInterface
{
    use ProductTrait;

    public function __construct(
        private readonly Product          $product,
        private readonly Translation      $translation,
        private readonly Tag              $tag,
        private readonly Cart             $cart,
        private readonly Wishlist         $wishlist,
        private readonly FlashDealProduct $flashDealProduct,
        private readonly DealOfTheDay     $dealOfTheDay,
    )
    {
    }

    public function addRelatedTags(object $request, object $product): void
    {
        $tagIds = [];
        if ($request->tags != null) {
            $tags = explode(",", $request->tags);
        }
        if (isset($tags)) {
            foreach ($tags as $value) {
                $tag = $this->tag->firstOrNew(
                    ['tag' => trim($value)]
                );
                $tag->save();
                $tagIds[] = $tag->id;
            }
        }
        $product->tags()->sync($tagIds);
    }

    public function add(array $data): string|object
    {
        return $this->product->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->product->where($params)->with($relations)->first();
    }

    public function getFirstWhereWithCount(array $params, array $withCount = [], array $relations = []): ?Model
    {
        return $this->product->with($relations)->where($params)->withCount($withCount)->first();
    }

    public function getFirstWhereWithoutGlobalScope(array $params, array $relations = []): ?Model
    {
        return $this->product->withoutGlobalScopes()->where($params)->with($relations)->first();
    }

    public function getFirstWhereActive(array $params, array $relations = []): ?Model
    {
        return $this->product->active()->where($params)->with($relations)->first();
    }

    public function getWebFirstWhereActive(array $params, array $relations = [], array $withCount = []): ?Model
    {
        return $this->product->active()
            ->when(isset($relations['reviews']), function ($query) use ($relations) {
                return $query->with($relations['reviews']);
            })
            ->when(isset($relations['seller.shop']), function ($query) use ($relations) {
                return $query->with($relations['seller.shop']);
            })
            ->when(isset($relations['wishList']), function ($query) use ($relations, $params) {
                return $query->with([$relations['wishList'] => function ($query) use ($params) {
                    return $query->when(isset($params['customer_id']), function ($query) use ($params) {
                        return $query->where('customer_id', $params['customer_id']);
                    });
                }]);
            })
            ->when(isset($relations['compareList']), function ($query) use ($relations, $params) {
                return $query->with([$relations['compareList'] => function ($query) use ($params) {
                    return $query->when(isset($params['customer_id']), function ($query) use ($params) {
                        return $query->where('user_id', $params['customer_id']);
                    });
                }]);
            })
            ->when(isset($params['slug']), function ($query) use ($params) {
                return $query->where('slug', $params['slug']);
            })
            ->when(isset($withCount['orderDetails']), function ($query) use ($withCount) {
                return $query->withCount($withCount['orderDetails']);
            })
            ->when(isset($withCount['wishList']), function ($query) use ($withCount) {
                return $query->withCount($withCount['wishList']);
            })
            ->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        // TODO: Implement getList() method.
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->product->with($relations)->when(isset($filters['added_by']) && $this->isAddedByInHouse(addedBy: $filters['added_by']), function ($query) {
            return $query->where(['added_by' => 'admin']);
        })->when(isset($filters['added_by']) && !$this->isAddedByInHouse($filters['added_by']), function ($query) use ($filters) {
            return $query->where(['added_by' => 'seller'])
                ->when(isset($filters['request_status']) && $filters['request_status'] != 'all', function ($query) use ($filters) {
                    $query->where(['request_status' => $filters['request_status']]);
                })
                ->when(isset($filters['seller_id'])&& $filters['seller_id']!='all', function ($query) use ($filters) {
                    return $query->where(['user_id' => $filters['seller_id']]);
                });
        })->when($searchValue, function ($query) use ($filters, $searchValue) {
            $product_ids = $this->translation->where('translationable_type', 'App\Models\Product')
                ->where('key', 'name')
                ->where('value', 'like', "%{$searchValue}%")
                ->pluck('translationable_id');

            return $query->where('name', 'like', "%{$searchValue}%")
                ->orWhere(function ($query) use ($filters) {
                    if (isset($filters['code'])) {
                        $query->where('code', 'like', "%{$filters['code']}%");
                    }
                })
                ->when(isset($filters['added_by']) && !$this->isAddedByInHouse($filters['added_by']), function($query) use($filters, $product_ids) {
                    $query->orWhereIn('id', $product_ids)
                        ->where(['added_by' => 'seller'])
                        ->when(isset($filters['seller_id']), function ($query) use ($filters) {
                            return $query->where(['user_id' => $filters['seller_id']]);
                        });
                })
                ->when(isset($filters['added_by']) && $this->isAddedByInHouse($filters['added_by']), function($query) use($filters, $product_ids) {
                    $query->orWhereIn('id', $product_ids)->where(['added_by' => 'admin']);
                });
        })->when(isset($filters['product_search_type']) && $filters['product_search_type'] == 'product_gallery', function ($query) use ($filters) {
            return $query->when(isset($filters['request_status']) && $filters['request_status'] != 'all', function ($query) use ($filters) {
                    $query->where(['request_status' => $filters['request_status']]);
                });
        })->when(isset($filters['brand_id']) && $filters['brand_id'] != 'all', function ($query) use ($filters) {
            return $query->where(['brand_id' => $filters['brand_id']]);
        })->when(isset($filters['category_id']) && $filters['category_id'] != 'all', function ($query) use ($filters) {
            return $query->where(['category_id' => $filters['category_id']]);
        })->when(isset($filters['sub_category_id']) && $filters['sub_category_id'] != 'all', function ($query) use ($filters) {
            return $query->where(['sub_category_id' => $filters['sub_category_id']]);
        })->when(isset($filters['sub_sub_category_id']) && $filters['sub_sub_category_id'] != 'all', function ($query) use ($filters) {
            return $query->where(['sub_sub_category_id' => $filters['sub_sub_category_id']]);
        })->when(isset($filters['is_shipping_cost_updated']), function ($query) use ($filters) {
            return $query->where(['is_shipping_cost_updated' => $filters['is_shipping_cost_updated']]);
        })->when(isset($filters['status']), function ($query) use ($filters) {
            return $query->where(['status' => $filters['status']]);
        })->when(isset($filters['code']), function ($query) use ($filters) {
            return $query->where(['code' => $filters['code']]);
        })->when(!empty($orderBy), function ($query) use ($orderBy) {
            $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
        });

        $filters += ['searchValue' => $searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function getListWithScope(array $orderBy = [], string $searchValue = null, string $scope = null, array $filters = [], array $whereIn = [], array $whereNotIn = [], array $relations = [], array $withCount = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->product->with($relations)
            ->when(isset($withCount['reviews']), function ($query) use ($withCount) {
                return $query->withCount($withCount['reviews']);
            })
            ->when(isset($scope) && $scope == 'active', function ($query) {
                return $query->active();
            })
            ->when($searchValue, function ($query) use ($searchValue) {
                $product_ids = $this->translation->where('translationable_type', 'App\Models\Product')
                    ->where('key', 'name')
                    ->where('value', 'like', "%{$searchValue}%")
                    ->pluck('translationable_id');
                return $query->where('name', 'like', "%{$searchValue}%")
                    ->orWhereIn('id', $product_ids);
            })
            ->when(isset($filters['search_from']) && $filters['search_from'] == 'pos', function ($query) use ($filters) {
                $searchKeyword = str_ireplace(['\'', '"', ',', ';', '<', '>', '?'], ' ', preg_replace('/\s\s+/', ' ', $filters['keywords']));
                return $query->where(function ($query) use ($filters) {
                    return $query->where('code', 'like', "%{$filters['keywords']}%")
                        ->orWhere('name', 'like', "%{$filters['keywords']}%");
                })
                ->orderByRaw("CASE WHEN name LIKE '%{$searchKeyword}%' THEN 1 ELSE 2 END, LOCATE('{$searchKeyword}', name), name");
            })
            ->when(isset($filters['added_by']) && $this->isAddedByInHouse(addedBy: $filters['added_by']), function ($query) {
                return $query->where(['added_by' => 'admin']);
            })->when(isset($filters['added_by']) && !$this->isAddedByInHouse($filters['added_by']), function ($query) use ($filters) {
                return $query->where(['added_by' => 'seller'])
                    ->when(isset($filters['request_status']), function ($query) use ($filters) {
                        $query->where(['request_status' => $filters['request_status']]);
                    })
                    ->when(isset($filters['seller_id']), function ($query) use ($filters) {
                        return $query->where(['user_id' => $filters['seller_id']]);
                    });
            })
            ->when(isset($filters['brand_id']), function ($query) use ($filters) {
                return $query->where(['brand_id' => $filters['brand_id']]);
            })->when(isset($filters['category_id']), function ($query) use ($filters) {
                return $query->where(['category_id' => $filters['category_id']]);
            })->when(isset($filters['sub_category_id']), function ($query) use ($filters) {
                return $query->where(['sub_category_id' => $filters['sub_category_id']]);
            })->when(isset($filters['status']), function ($query) use ($filters) {
                return $query->where(['status' => $filters['status']]);
            })->when(isset($whereIn), function ($query) use ($whereIn) {
                foreach ($whereIn as $key => $whereInIndex) {
                    return $query->whereIn($key, $whereInIndex);
                }
            })
            ->when(isset($filters['sub_sub_category_id']), function ($query) use ($filters) {
                return $query->where(['sub_sub_category_id' => $filters['sub_sub_category_id']]);
            })->when($whereNotIn, function ($query) use ($whereNotIn) {
                foreach ($whereNotIn as $key => $whereNotInIndex) {
                    $query->whereNotIn($key, $whereNotInIndex);
                }
            })->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
            });

        $filters += ['searchValue' => $searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function getWebListWithScope(array $orderBy = [], string $searchValue = null, string $scope = null, array $filters = [], array $whereHas = [], array $whereIn = [], array $whereNotIn = [], array $relations = [], array $withCount = [], array $withSum = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->product
            ->when(isset($scope) && $scope == 'active', function ($query) {
                return $query->active();
            })
            ->when(isset($filters['added_by']) && $this->isAddedByInHouse(addedBy: $filters['added_by']), function ($query) {
                return $query->where(['added_by' => 'admin']);
            })->when(isset($filters['added_by']) && !$this->isAddedByInHouse($filters['added_by']), function ($query) use ($filters) {
                return $query->where(['added_by' => 'seller']);
            })
            ->when(isset($relations['reviews']), function ($query) use ($relations) {
                return $query->with($relations['reviews'], function ($query) use($relations) {
                    return $query->active();
                });
            })
            ->when(isset($relations['seller.shop']), function ($query) use ($relations) {
                return $query->with($relations['seller.shop']);
            })
            ->when(isset($relations['flashDealProducts.flashDeal']), function ($query) use ($relations) {
                return $query->with($relations['flashDealProducts.flashDeal']);
            })
            ->when(isset($relations['wishList']), function ($query) use ($relations, $filters) {
                return $query->with([$relations['wishList'] => function ($query) use ($filters) {
                    return $query->when(isset($filters['customer_id']), function ($query) use ($filters) {
                        return $query->where('customer_id', $filters['customer_id']);
                    });
                }]);
            })
            ->when(isset($relations['compareList']), function ($query) use ($relations, $filters) {
                return $query->with([$relations['compareList'] => function ($query) use ($filters) {
                    return $query->when(isset($filters['customer_id']), function ($query) use ($filters) {
                        return $query->where('user_id', $filters['customer_id']);
                    });
                }]);
            })
            ->when(isset($whereHas['reviews']), function ($query) use ($whereHas) {
                return $query;
            })
            ->when(isset($withCount['reviews']), function ($query) use ($withCount) {
                return $query->withCount([$withCount['reviews'] => function ($query) {
                    return $query->active();
                }]);
            })
            ->when($withSum, function ($query) use ($withSum) {
                foreach ($withSum as $sum) {
                    return $query->withSum($sum['relation'], $sum['column'], function ($query) use ($sum) {
                        $query->where($sum['whereColumn'], $sum['whereValue']);
                    });
                }
                return $query->withSum($withSum['orderDetails']);
            })
            ->when(isset($withSum['qty']), function ($query) use ($withSum) {
                return $query->withSum($withSum['qty']);
            })
            ->when($searchValue, function ($query) use ($searchValue) {
                $product_ids = $this->translation->where('translationable_type', 'App\Models\Product')
                    ->where('key', 'name')
                    ->where('value', 'like', "%{$searchValue}%")
                    ->pluck('translationable_id');
                return $query->where('name', 'like', "%{$searchValue}%")->orWhereIn('id', $product_ids);
            })->when(isset($filters['seller_id']), function ($query) use ($filters) {
                return $query->where('user_id', $filters['seller_id']);
            })->when(isset($filters['brand_id']), function ($query) use ($filters) {
                return $query->where(['brand_id' => $filters['brand_id']]);
            })->when(isset($filters['category_id']), function ($query) use ($filters) {
                return $query->where(['category_id' => $filters['category_id']]);
            })->when(isset($filters['sub_category_id']), function ($query) use ($filters) {
                return $query->where(['sub_category_id' => $filters['sub_category_id']]);
            })->when(isset($whereIn), function ($query) use ($whereIn) {
                foreach ($whereIn as $key => $whereInIndex) {
                    return $query->whereIn($key, $whereInIndex);
                }
            })->when(isset($filters['sub_sub_category_id']), function ($query) use ($filters) {
                return $query->where(['sub_sub_category_id' => $filters['sub_sub_category_id']]);
            })->when($whereNotIn, function ($query) use ($whereNotIn) {
                foreach ($whereNotIn as $key => $whereNotInIndex) {
                    $query->whereNotIn($key, $whereNotInIndex);
                }
            })->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
            });

        $filters += ['searchValue' => $searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }
    public function update(string $id, array $data): bool
    {
        return $this->product->where('id', $id)->update($data);
    }
    public function updateByParams(array $params, array $data): bool
    {
        return $this->product->where($params)->update($data);
    }


    public function getListWhereNotIn(array $filters = [], array $whereNotIn = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        return $this->product->when($whereNotIn, function ($query) use ($whereNotIn) {
            foreach ($whereNotIn as $key => $whereNotInIndex) {
                $query->whereNotIn($key, $whereNotInIndex);
            }
        })->when(isset($filters['user_id']), function ($query) use ($filters) {
            return $query->where(['user_id' => $filters['user_id']]);
        })->when(isset($filters['added_by']), function ($query) use ($filters) {
            return $query->where(['added_by' => $filters['added_by']]);
        })->get();
    }

    public function getTopRatedList(array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        return $this->product->with($relations)->where($filters)
            ->with('reviews', function ($query) {
                return $query->whereHas('product', function ($query) {
                    $query->active();
                });
            })
            ->withCount(['reviews' => function ($query){
                return $query->whereNull('delivery_man_id');
            }])
            ->withAvg('rating as ratings_average', 'rating')
            ->orderByDesc('reviews_count')
            ->get();
    }

    public function getTopSellList(array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        return $this->product->with($relations)
            ->when(isset($filters['added_by']) && $this->isAddedByInHouse(addedBy: $filters['added_by']), function ($query) {
                return $query->where(['added_by' => 'admin']);
            })->when(isset($filters['added_by']) && !$this->isAddedByInHouse($filters['added_by']), function ($query) use ($filters) {
                return $query->where(['added_by' => 'seller', 'request_status' => $filters['request_status']]);
            })->when(isset($filters['seller_id']), function ($query) use ($filters) {
                return $query->where('user_id', $filters['seller_id']);
            })
            ->when(isset($filters['request_status']), function ($query) use ($filters) {
                return $query->where('request_status', $filters['request_status']);
            })
            ->whereHas('orderDetails', function ($query) {
                $query->where(['delivery_status' => 'delivered']);
            })
            ->withCount('orderDetails')->get()
            ->sortByDesc('order_details_count');
    }

    public function delete(array $params): bool
    {
        return $this->product->where($params)->delete();
    }

    public function getStockLimitListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $withCount = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $stockLimit = getWebConfig(name: 'stock_limit');
        $query = $this->product->with($relations)
            ->withCount($withCount)
            ->when($this->isAddedByInHouse(addedBy: $filters['added_by']), function ($query) {
                return $query->where(['added_by' => 'admin']);
            })
            ->when(!$this->isAddedByInHouse($filters['added_by']), function ($query) use ($filters) {
                return $query->where(['added_by' => 'seller', 'product_type' => 'physical'])
                    ->when(isset($filters['request_status']), function ($query) use ($filters) {
                        return $query->where(['request_status' => $filters['request_status']]);
                    })
                    ->when(isset($filters['seller_id']), function ($query) use ($filters) {
                        return $query->where(['user_id' => $filters['seller_id']]);
                    });
            })
            ->when(isset($filters['product_type']), function ($query) use ($filters) {
                return $query->where(['product_type' => $filters['product_type']]);
            })
            ->when($searchValue, function ($query) use ($searchValue) {
                $product_ids = $this->translation->where('translationable_type', 'App\Models\Product')
                    ->where('key', 'name')
                    ->where('value', 'like', "%{$searchValue}%")
                    ->pluck('translationable_id');

                return $query->where('name', 'like', "%{$searchValue}%")->orWhereIn('id', $product_ids);
            })
            ->where('current_stock', '<', $stockLimit)
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                return $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
            });

        $filters += ['searchValue' => $searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function getProductIds(array $filters = []): \Illuminate\Support\Collection|array
    {
        return $this->product->when(isset($filters['added_by']), function ($query) use ($filters) {
            return $query->where('added_by', $filters['added_by']);
        })->when(isset($filters['user_id']), function ($query) use ($filters) {
            return $query->where('user_id', $filters['user_id']);
        })->pluck('id');

    }

    public function addArray(array $data): bool
    {
        return DB::table('products')->insert($data);
    }
}
