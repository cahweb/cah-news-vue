<template>
    <div class="col news-item">
        <a :href="post.link" target="_blank" rel="noopener">
            <div class="row">
                <div class="col-3">
                    <img :src="post.featured_media" :alt="post.title.rendered" :title="post.title.rendered">
                </div>
                <div class="col-9">
                    <h5 class="mt-0">{{ post.title.rendered }}</h5>
                    <p><span class="news-date">{{ displayDate }}</span> &bull; {{ post.excerpt.rendered }}</p>
                </div>
            </div>
        </a>
    </div>
</template>

<script setup>
import {defineProps, computed} from 'vue'
import {useStore} from 'vuex'

const props = defineProps({
    id: {
        type: Number,
        required: true,
    },
})

const store = useStore()

const post = computed(() => store.getters.getNewsItem(props.id))

const displayDate = computed(() => {
    const rawDate = post.value.date

    const date = new Date(rawDate)

    return date.toLocaleDateString('en-us', {year: 'numeric', day: 'numeric', month: 'short'})
})

</script>

<style lang="scss" scoped>

</style>