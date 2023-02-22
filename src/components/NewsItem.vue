<template>
    <div class="col-12 py-2 news-item">
        <a :href="post.link" target="_blank" rel="noopener">
            <div class="news-item-content">
                <div class="news-thumbnail">
                    <img :src="post.featured_media" :alt="post.title.rendered" :title="post.title.rendered">
                </div>
                <div class="news-text">
                    <h5 class="mt-0" v-html="post.title.rendered" />
                    <p><span class="news-date text-muted">{{ displayDate }}</span> &bull; {{ specCharDecode(post.excerpt.rendered) }}</p>
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

function specCharDecode(inputStr) {
    return inputStr.replace(/(&#(\d+);)/g, (match, capture, charCode) => String.fromCharCode(charCode))
}

</script>

<style lang="scss" scoped>
.news-thumbnail {
    margin-right: 1.5em;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;

    img {
        width: 150px;
        height: 150px;
        object-fit: cover;
    }
}

.news-item {
    a {
        color: #000;
        text-decoration: none;
    }

    &:hover {
        background-color: #c4cfd4;
    }
}

.news-item-content {
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    justify-content: flex-start;
    align-items: start;
}
</style>