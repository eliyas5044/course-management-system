<template>
  <div v-if="course" class="row">
    <div class="col">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title">
            {{ course.title }}
          </h5>
        </div>
        <div class="card-body">
          <p class="card-text">
            {{ course.content }}
          </p>
        </div>
      </div>
      <br>
      <card title="Lessons">
        <template v-slot>
          <ul class="list-group">
            <li v-for="(lesson, index) in course.lessons" :key="index" class="list-group-item">
              <router-link :to="`/lessons/${index}`">
                {{ lesson }}
              </router-link>
            </li>
          </ul>
        </template>
      </card>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'CourseSingle',
  middleware: 'guest',
  metaInfo () {
    return { title: this.course.title }
  },
  data: () => ({
    course: {
      id: null,
      title: '',
      excerpt: '',
      content: '',
      lessons: []
    }
  }),
  mounted () {
    this.getCourse()
  },
  methods: {
    // get course
    async getCourse () {
      try {
        const { data } = await axios.get('/api/course/' + this.$route.params.slug)

        this.course = data
      } catch (e) {}
    }
  }
}
</script>
