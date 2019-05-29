<template>
  <div v-if="lesson" class="row">
    <div class="col">
      <card :title="lesson.title">
        <template v-slot:action>
          <router-link :to="`/dashboard/lessons/entry?id=${lesson.id}`" class="btn btn-primary">
            <fa icon="pen" /> Edit
          </router-link>
        </template>
        <template v-slot>
          {{ lesson.content }}
        </template>
      </card>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'LessonSingle',
  middleware: 'auth',
  metaInfo () {
    return { title: this.lesson.title }
  },
  data: () => ({
    lesson: {
      id: null,
      title: '',
      excerpt: '',
      content: ''
    }
  }),
  mounted () {
    this.getLesson()
  },
  methods: {
    // get lesson
    async getLesson () {
      try {
        const { data } = await axios.get('/api/admin/lesson/' + this.$route.params.id)

        this.lesson = data
      } catch (e) {}
    }
  }
}
</script>
