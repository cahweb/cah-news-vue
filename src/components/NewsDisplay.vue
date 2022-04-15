<template>
    <div class="row">
        <div v-for="(id, i) of store.state.displayList" :key="i" class="col">
            <NewsItem :id="id" />
        </div>
    </div>
</template>

<script setup>
import {useStore} from 'vuex'

import NewsItem from './NewsItem.vue'

const store = useStore()
</script>

<style lang="scss" scoped>

</style>