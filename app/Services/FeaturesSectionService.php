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
            $image = '';
            if (!empty($request['features_section_bottom_icon']) && isset($request['features_section_bottom_icon'][$key])) {
                $image = $this->upload(dir: 'banner/', format: 'webp', image: $request['features_section_bottom_icon'][$key]);
            }
            $bottomSectionData[] = [
                'title' => $request['features_section_bottom']['title'][$key],
                'subtitle' => $request['features_section_bottom']['subtitle'][$key],
                'icon' => $image,
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
                $this->delete(filePath: "/banner/" . $item->icon);
            }
        }
        return $newArray;
    }

    public function getReliabilityUpdateData(object $request, object $data): array
    {
        $image = '';
        $item = [];
        if ($request->has('image')) {
            $image = $this->upload(dir: 'company-reliability/', format: 'webp', image: $request->file('image'));
        }

        foreach (json_decode($data['value'], true) as $key => $data) {
            if ($data['item'] == $request['item']) {
                $item_data = [
                    'item' => $request['item'],
                    'title' => $request->title ?? '',
                    'image' => $image === '' ? $data['image'] : $image,
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
        }

        return $item;
    }

}
