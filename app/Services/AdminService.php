<?php

namespace App\Services;

use App\Contracts\AdminServiceInterface;
use App\Traits\FileManagerTrait;

class AdminService implements AdminServiceInterface
{
    use FileManagerTrait;

    public function isLoginSuccessful(string $email, string $password, string|null|bool $rememberToken): bool
    {
        if (auth('admin')->attempt(['email' => $email, 'password' => $password], $rememberToken)) {
            return true;
        }
        return false;
    }

    public function logout(): void
    {
        auth()->guard('web')->logout();
        session()->invalidate();
    }

    public function getIdentityImages(object $request, object $oldImages = null): bool|string
    {
        if (!empty($oldImages['identify_image'])) {
            foreach (json_decode($oldImages['identify_image'], true) as $image) {
                $this->delete('admin/' . $image);
            }
        }

        $identity_images = [];
        if (!empty($request->file('identity_image'))) {
            foreach ($request['identity_image'] as $img) {
                $identity_images[] = $this->upload('admin/', 'webp', $img);
            }
            $identity_images = json_encode($identity_images);
        } else {
            $identity_images = json_encode([]);
        }

        return $identity_images;
    }

    public function getProceedImage(object $request, string $oldImage = null): bool|string
    {
        if ($oldImage) {
            $image =  $this->update('admin/', $oldImage,  'webp', $request['image']);
        }else {
            $image =  $this->upload('admin/', 'webp', $request['image']);
        }
        return $image;
    }

    /**
     * @return array[f_name: mixed, l_name: mixed, phone: mixed, image: mixed|string]
     */
    public function getAdminDataForUpdate(object $request, object $admin):array
    {
        $image = $request['image'] ? $this->update(dir: 'admin/', oldImage: $admin['image'], format: 'webp', image: $request->file('image')) : $admin['image'];
        return [
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'image' => $image,
        ];
    }

    /**
     * @return array[password: string]
     */
    public function getAdminPasswordData(object $request):array
    {
        return [
            'password' => bcrypt($request['password']),
        ];
    }

}
