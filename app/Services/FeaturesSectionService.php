<?php

namespace App\Services;

use App\Traits\FileManagerTrait;

class FeaturesSectionService
{
    use FileManagerTrait;

    public function getBottomSectionData(object $request, object $featuresBottomSection = null): array
    {
        $bottomSectionData = [];
        if($featuresBottomSection) {
            $bottomSectionData = json_decode($featuresBottomSection['value']);
        }
        foreach($request['features_section_bottom']['title'] as $key => $value) {
            $iconArray = null;
            if (!empty($request['features_section_bottom_icon']) && isset($request['features_section_bottom_icon'][$key])) {
                $image = $this->upload(dir: 'banner/', format: 'webp', image: $request['features_section_bottom_icon'][$key]);
                $iconArray = [
                    'image_name' =>  $image,
                    'storage' => config('filesystems.disks.default') ?? 'public'
                ];
            }
            $bottomSectionData[] = [
                'title' => $request['features_section_bottom']['title'][$key],
                'subtitle' => $request['features_section_bottom']['subtitle'][$key],
                'icon' => $iconArray,
            ];
        }
        return $bottomSectionData;
    }

    public function getDeleteData(object $request, object $data): array
    {
        $newArray = [];
        foreach(json_decode($data->value) as $item) {
            if($request->title != $item->title && $request->subtitle != $item->subtitle){
                $newArray[] = $item;
            }else{
                $this->delete(filePath: "/banner/" . ($item?->icon?->image_name ?? $item?->icon));
            }
        }
        return $newArray;
    }

    public function getReliabilityUpdateData(object $request, object $data): array
    {

        $item = [];
        $imageArray = null;
        foreach (json_decode($data['value'], true) as $key => $data) {
            if ($request->has('image') && $data['item'] == $request['item']){

                $imageArray = [
                    'image_name' =>  $this->update(dir: 'company-reliability/', oldImage: (is_array($data['image']) ? $data['image']['image_name'] : $data['image'] ), format: 'webp', image: $request->file('image')),
                    'storage' => config('filesystems.disks.default') ?? 'public'
                ];
            }
            if ($data['item'] == $request['item']) {
                $item_data = [
                    'item' => $request['item'],
                    'title' => $request->title ?? '',
                    'image' => $request->has('image') ? $imageArray : $data['image'],
                    'status' => $request->status ?? 0,
                ];
            } else {
                $item_data = [
                    'item' => $data['item'],
                    'title' => $data['title'],
                    'image' => $data['image'] ,
                    'status' => $data['status'] ??0,
                ];
            }
            $item[] = $item_data;
            $imageArray = null;
        }

        return $item;
    }

}
