<template>
  <div v-if="course" class="row">
    <div class="col">
      <card :title="course.title">
        <template v-slot:action>
          <router-link :to="`/dashboard/courses/entry?id=${course.id}`" class="btn btn-primary">
            <fa icon="pen" /> Edit
          </router-link>
        </template>
        <template v-slot>
          {{ course.content }}
        </template>
      </card>
      <br>
      <card title="Lessons">
        <template v-slot>
          <ul class="list-group">
            <li v-for="(lesson, index) in course.lessons" :key="index" class="list-group-item">
              <router-link :to="`/dashboard/lessons/${index}`">
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
  middleware: 'auth',
  metaInfo () {
    return { title: this.course.title }
  },
  data: () => ({
    course: {
      id: null,
      title: '',
      excerpt: '',
      content: ''
    }
  }),
  mounted () {
    this.getCourse()
  },
  methods: {
    // get course
    async getCourse () {
      try {
        const { data } = await axios.get('/api/admin/course/' + this.$route.params.id)

        this.course = data
      } catch (e) {}
    }
  }
}
</script>
