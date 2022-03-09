<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Components\RODate;

class FacturaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//        dd($this->resource);
        $result = parent::toArray($request);
        if($this->resource) {
            unset($result['Continut']);
            $result['Interval'] = RODate::getIntervalLuni($this->LunaIni, $this->LunaFin);
 //           $result['Sold'] = $this->Sold;
        }
//dd($result);
         return $result;

    }
}
