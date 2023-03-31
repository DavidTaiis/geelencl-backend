<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\CategoryByProduct;
use App\Models\Company;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use App\Jobs\UploadImagesProductJob;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;
use App\Repositories\ProductRepository;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function prepareForValidation(array $row)
    {
        $row['categories'] = explode(",", $row['categories']);
        return $row;
    }

    public function model(array $row)
    {
        $product = ProductRepository::saveUpload($row);
        UploadImagesProductJob::dispatch($row['images'], $product->id)->onQueue('images_products_upload');
    }

    public function rules(): array
    {
        return [
            '*.code_company' => 'required|string|exists:company,code',
            '*.name' => 'required',
            '*.code' => 'required',
            'categories.*' => 'required|string|exists:category,name',
            '*.points' => 'required|integer',
            '*.status' => 'required|in:ACTIVE,INACTIVE',
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'status.in' => 'El campo status debe ser: ACTIVE ó INACTIVE',
            'status.required' => 'El campo status es obligatorio',
            'points.required' => 'El campo points es requerido',
            'name.required' => 'El campo name es obligatorio',
            'code.required' => 'El campo code  es requerido',
            'points.integer' => 'El campo points debe ser un número entero',
            'code_company.required' => 'El código de la empresa es requerido',
            'code_company.exists' => 'El código de la empresa no existe',
            'categories.required' => 'El campo de categorías es requerido',
            'code_company.string' => 'El código de la empresa debe ser una cadena de texto',
            'categories.string' => 'El campo de categorías ser una cadena de texto',
        ];
    }
}
