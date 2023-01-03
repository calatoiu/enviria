<template>
    <div v-if='props.fvalue' class="p-1 m-1 border rounded">
        {{props.fvalue}}
    </div>
</template>
<script setup>
const props = defineProps({
    fvalue: {String, Number},
})
</script>
