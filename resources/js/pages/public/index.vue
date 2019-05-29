<template>
  <div>
    <template v-if="courses.length">
      <div class="row">
        <div v-for="course in courses" :key="course.id" class="col-sm-6">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title">
                {{ course.title }}
              </h5>
            </div>
            <div class="card-body">
              <p class="card-text">
                {{ course.excerpt }}
              </p>
              <router-link :to="`/courses/${course.slug}`" class="btn btn-primary">
                See More
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </template>
    <template v-else>
      <div class="d-flex align-items-center justify-content-center" style="height: 80vh">
        <h1>{{ title }}</h1>
      </div>
    </template>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'PublicCourse',
  middleware: 'guest',
  metaInfo () {
    return { title: 'Home' }
  },
  data: () => ({
    courses: [],
    title: window.config.appName
  }),
  mounted () {
    this.getCourses()
  },
  methods: {
    // get courses
    async getCourses () {
      try {
        const { data } = await axios.get('/api/course')
        this.courses = data
      } catch (e) {}
    }
  }
}
</script>
