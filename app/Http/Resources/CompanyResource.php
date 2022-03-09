<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//         $e = new \Exception();
//         echo('<PRE>');
//         var_dump($e->getTraceAsString());
// die();
        // $rr = parent::toArray($request);
        // dd($request);
        return parent::toArray($request);
    }
}
