/* eslint no-undef: 1 */
/* eslint no-console: 1 */
import { createStore } from 'vuex'

//import axios from 'axios'
import _ from 'lodash'

const state = {
  restUri: wpVars.restUri,
  news: {},
  displayList: [],
  dept: 11,
  limit: -1,
  per_page: 20,
  view: 'full',
  cat: [],
  exclude: [],
  section_title: "In the News",
  section_title_classes: '',
  button_text: "More News",
  button_href: '',
  new_tab: false,
  tags: '',
}

export default createStore({
  state,
  getters: {
    getNewsItem: state => (id) => state.news[id],
  },
  mutations: {
    updateState(state, {name, value}) {
      if (state[name] === undefined) {
        const newState = state
        newState[name] = value
        state = newState
      } else {
        state[name] = value
      }
    }
  },
  actions: {
    async getOptions({commit}) {
      const options = JSON.parse(_.unescape(document.querySelector('#cah-news-vue-options').value))

      for (const [key, value] of Object.entries(options)) {
        commit('updateState', {name: key, value})
      }
    },
    async getNews({state}) {
      const url = state.restUri
      console.log(url)
      /*
      let url = `${state.restUri}/news`

      const queryArgs = {
        dept: state.dept.join(','),
        tags: state.tags,
        limit: state.limit,
      }

      url += `?${Object.entries(queryArgs).filter(([,value]) => value?.length).map(([key, value]) => `${key}=${value}`).join('&')}`
      const restResponse = await axios.get(url)
        .then(response => response.data)
        .catch(err => {console.error(err)})

      console.log(restResponse)
      */
    },
    async doInit({dispatch}) {
      await dispatch('getOptions')
      dispatch('getNews')
    },
  },
  modules: {
  }
})
