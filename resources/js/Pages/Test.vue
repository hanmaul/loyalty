<script setup>

defineProps({
    token: String,
    custid: String,
});

</script>
<template>
    <h1>Parameter Token: {{token}}</h1>
    
    <h1>Parameter Custid: {{custid}}</h1>
    <!-- <p>{{ parameter.key }}</p> -->
    <!-- <h1>Parameter CustId: </h1> -->
    <!-- //<p>{{ parameter.custid }}</p> -->
    <!-- <p>{{key}} {{custid}}</p> -->
</template>