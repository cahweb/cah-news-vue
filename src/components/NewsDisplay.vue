<template>
    <div class="row">
        <NewsItem v-for="(id, i) of store.getters.displayList" :key="i" :id="id" />
    </div>
</template>

<script setup>
import {useStore} from 'vuex'

import NewsItem from './NewsItem.vue'

const store = useStore()
</script>

<style lang="scss" scoped>

</style>