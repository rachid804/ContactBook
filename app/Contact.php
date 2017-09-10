<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * Convert form array to associated array and then json format
     * @param array $value
     */
    public function setCustomFieldsAttribute($value)
    {
        $customFields = [];
        foreach ($value as $cf) {
            $customFields[str_slug($cf['fieldName'], '_')] = $cf['fieldValue'];
        }

        $this->attributes['custom_fields'] = json_encode($customFields);
    }

    /**
     * Convert json format to array
     * @param $customFields
     * @return mixed
     */
    public function getCustomFieldsAttribute($customFields)
    {
        return json_decode($customFields);
    }


}
