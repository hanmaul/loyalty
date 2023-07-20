<script setup>
import BottomNavigation from '@/Components/Loyalty/BottomNavigation.vue';
import Nav from '@/Components/Loyalty/Navbar.vue';
import AjuDanaForm from '@/Pages/Loyalty/AjuDanaForm.vue';
</script>
<template>
    <Nav></Nav>
    <AjuDanaForm></AjuDanaForm>
    <!-- <main>
        <slot />
    </main> -->
    <BottomNavigation></BottomNavigation>
</template>
