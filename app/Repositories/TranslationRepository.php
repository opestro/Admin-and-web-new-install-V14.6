<?php

namespace App\Repositories;

use App\Contracts\Repositories\TranslationRepositoryInterface;
use App\Models\Translation;

class TranslationRepository implements TranslationRepositoryInterface
{
    public function __construct(
       private readonly Translation $translation
    )
    {
    }

    public function add(object $request, string $model, int|string $id): bool
    {
        foreach ($request->lang as $index => $key) {
            foreach (['name','description','title'] as $type){
                if (isset($request[$type][$index]) && $key != 'en') {
                    $this->translation->insert(
                        [
                            'translationable_type' => $model,
                            'translationable_id' => $id,
                            'locale' => $key,
                            'key' => $type,
                            'value' => $request[$type][$index]
                        ]
                    );
                }
            }
        }
        return true;
    }

    public function update(object $request, string $model, int|string $id): bool
    {
        foreach ($request->lang as $index => $key) {
            foreach (['name','description','title'] as $type){
                if (isset($request[$type][$index]) && $key != 'en') {
                    $this->translation->updateOrInsert(
                        [
                            'translationable_type' => $model,
                            'translationable_id' => $id,
                            'locale' => $key,
                            'key' => $type
                        ],
                        [
                            'value' => $request[$type][$index]
                        ]
                    );
                }
            }
        }
        return true;
    }
    public function updateData(string $model, string $id, string $lang, string $key, string $value):bool
    {
        $this->translation->updateOrInsert(
            [
                'translationable_type' => $model,
                'translationable_id' => $id,
                'locale' => $lang,
                'key' => $key
            ],
            [
                'value' => $value
            ]
        );
        return true;
    }
    public function delete(string $model, int|string $id): bool
    {
        $this->translation->where('translationable_type',$model)->where('translationable_id',$id)->delete();
        return true;
    }
}
