<template>
<div class="space-y-2">
    <div class="flex items-end">
        <Label class="flex-none" :for="props.fieldName" :value="props.labelName" />
            <div class="flex-initial pl-3" >
                <fieldset class="flex border border-gray-400 border-solid rounded dark:border-gray-600">
                    <legend class="text-xs font-thin text-gray-700 dark:text-gray-300">
                        <div class="px-0.5 italic" > ANAF </div>
                    </legend>
                    <div class="px-2 text-xs font-normal text-gray-500 dark:text-gray-300"> {{props.anafFieldValue}} </div>
                    <Button
                        iconOnly variant="secondary" size="xs" type="button"
                        @click="copyField"
                        v-slot="{ iconSizeClasses }"
                    >
                        <ChevronDoubleDownIcon
                            aria-hidden="true"
                            :class="iconSizeClasses"
                        />
                    </Button>
                </fieldset>
            </div>
    </div>
    <InputIconWrapper>
        <template #icon>
            <PencilAltIcon aria-hidden="true" class="w-5 h-5" />
        </template>
        <InputVee withIcon :name="props.fieldName" :id="props.fieldName" type="text" class="block w-full" placeholder="props.labelName"

        v-model="props.fieldValue"
        :rules="validateField"

        />

    </InputIconWrapper>
    <ErrorMessage name="denumire" />
</div>
</template>

<script setup>
import InputVee from '@/Components/InputVee'
import Label from '@/Components/Label'
import InputIconWrapper from '@/Components/InputIconWrapper'
import { PlusIcon, PlusCircleIcon, SearchIcon, PencilAltIcon, SaveIcon, XIcon, ChevronDoubleDownIcon } from '@heroicons/vue/outline'


import { defineProps, defineEmits, ref, computed
//reactive, onMounted,
} from 'vue'

import { Field, Form, ErrorMessage } from 'vee-validate';
import Button from '@/Components/Button'

const props = defineProps({
    fieldName: String,
    labelName: String,
    fieldValue: String,
    anafFieldValue: String,
 //   validateField,

})

const copyField = () => {
    // console.log("copyDenumire")
     console.log("props.anafFieldValue", props.anafFieldValue)
     console.log("props.fieldValue", props.fieldValue)
   props.fieldValue = props.anafFieldValue
   console.log("props.anafFieldValue", props.anafFieldValue)
     console.log("props.fieldValue", props.fieldValue)
}

const validateField = (value) => {
      // if the field is empty
      if (!value) {
        return 'This field is required';
      }
      // if the field is not a valid email
    //   const regex = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
    //   if (!regex.test(value)) {
    //     return 'This field must be a valid email';
    //   }
      // All is good
      return true;
}
</script>
