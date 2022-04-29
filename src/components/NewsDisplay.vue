<template>
    <div class="row">
        <TransitionGroup name="fadeIn" appear>
            <NewsItem v-for="(id, i) of store.getters.displayList" :key="i" :id="id" />
        </TransitionGroup>
    </div>
</template>

<script setup>
import {useStore} from 'vuex'

import NewsItem from './NewsItem.vue'

const store = useStore()
</script>

<style lang="scss" scoped>
.fadeIn-enter-active,
.fadeIn-leave-active {
    transition: opacity 1s ease;
}

.fadeIn-enter-from,
.fadeIn-leave-to {
    opacity: 0;
}
</style>