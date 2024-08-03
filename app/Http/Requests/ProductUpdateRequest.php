<?php

namespace App\Http\Requests;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Traits\CalculatorTrait;
use App\Traits\ResponseHandler;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ProductUpdateRequest extends FormRequest
{
    use CalculatorTrait, ResponseHandler;
    protected $stopOnFirstFailure = true;

    public function __construct(
        private readonly ProductRepositoryInterface $productRepo
    )
    {}

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $product = $this->productRepo->getFirstWhere(['id' => $this->route('id')]);

        return [
            'name' => 'required',
            'category_id' => 'required',
            'product_type' => 'required',
            'digital_product_type' => 'required_if:product_type,==,digital',
            'digital_file_ready' => 'mimes:jpg,jpeg,png,gif,zip,pdf',
            'unit' => 'required_if:product_type,==,physical',
            'tax' => 'required|min:0',
            'tax_model' => 'required',
            'unit_price' => 'required|numeric|gt:0',
            'discount' => 'required|gt:-1',
            'shipping_cost' => 'required_if:product_type,==,physical|gt:-1',
            'minimum_order_qty' => 'required|numeric|min:1',
            'code' => [
                'required',
                'regex:/^[a-zA-Z0-9]+$/',
                'min:6',
                'max:20',
                Rule::unique('products', 'code')->ignore($product->id, 'id'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required!',
            'category_id.required' => 'category  is required!',
            'unit.required_if' => 'Unit  is required!',
            'code.max' => translate('please_ensure_your_code_does_not_exceed_20_characters'),
            'code.min' => translate('code_with_a_minimum_length_requirement_of_6_characters'),
            'minimum_order_qty.required' => 'Minimum order quantity is required!',
            'minimum_order_qty.min' => 'Minimum order quantity must be positive!',
            'digital_file_ready.mimes' => 'Ready product upload must be a file of type: pdf, zip, jpg, jpeg, png, gif.',
            'digital_product_type.required_if' => 'Digital product type is required!',
            'shipping_cost.required_if' => 'Shipping Cost is required!',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $product = $this->productRepo->getFirstWhere(['id' => $this->route('id')]);
                $productImages = json_decode($product['images']);

                if (!$this->has('colors_active') && !$this->file('images') && empty($productImages)) {
                    $validator->errors()->add(
                        'images', translate('product_images_is_required') . '!'
                    );
                }

                if (getWebConfig(name: 'product_brand') && empty($this->brand_id)) {
                    $validator->errors()->add(
                        'brand_id', translate('brand_is_required') . '!'
                    );
                }

                if ($this['product_type'] == 'physical' && $this['unit_price'] <= $this->getDiscountAmount(price: $this['unit_price'], discount: $this['discount'], discountType: $this['discount_type'])) {
                    $validator->errors()->add(
                        'unit_price', translate('discount_can_not_be_more_or_equal_to_the_price') . '!'
                    );
                }

                if (is_null($this['name'][array_search('EN', $this['lang'])])) {
                    $validator->errors()->add(
                        'name', translate('name_field_is_required') . '!'
                    );
                }

                if ($this->has('colors_active') && $this->has('colors') && count($this['colors']) > 0) {
                    $databaseColorImages = $product['color_image'] ? json_decode($product['color_image'], true) : [];
                    if (!$databaseColorImages) {
                        foreach ($productImages as $image) {
                            $databaseColorImages[] = ['color' => null,'image_name' => $image];
                        }
                    }
                    $databaseColorImagesFinal = [];
                    if ($databaseColorImages) {
                        foreach ($databaseColorImages as $colorImage) {
                            if ($colorImage['color']) {
                                $databaseColorImagesFinal[] = $colorImage['color'];
                            }
                        }
                    }
                    $inputColors = [];
                    foreach ($this['colors'] as $color) {
                        $inputColors[] = str_replace('#', '', $color);
                    }
                    $differentColor = array_diff($databaseColorImagesFinal, $inputColors);
                    $colorImageRequired = [];
                    if ($databaseColorImages) {
                        foreach ($databaseColorImages as $colorImage) {
                            if ($colorImage['color'] != null && !in_array($colorImage['color'], $differentColor)) {
                                $colorImageRequired[] = [
                                    'color' => $colorImage['color'],
                                    'image_name' => $colorImage['image_name'],
                                ];
                            }
                        }
                    }

                    foreach ($inputColors as $color) {
                        if (!in_array($color, $databaseColorImagesFinal)) {
                            $colorImageIndex = 'color_image_' . $color;
                            if ($this->file($colorImageIndex)) {
                                $colorImageRequired[] = ['color' => $color, 'image_name' => rand(11111, 99999)];
                            }
                        }
                    }

                    if (count($colorImageRequired) != count($this['colors'])) {
                        $validator->errors()->add(
                            'images', translate('Color_images_is_required')
                        );
                    }
                }

            }
        ];
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $this->errorProcessor($validator)]));
    }
}
