<template>
  <div v-if="lesson" class="row">
    <div class="col">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title">
            {{ lesson.title }}
          </h5>
        </div>
        <div class="card-body">
          <p class="card-text">
            {{ lesson.content }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'LessonSingle',
  middleware: 'guest',
  metaInfo () {
    return { title: this.lesson.title }
  },
  data: () => ({
    lesson: {
      id: null,
      title: '',
      excerpt: '',
      content: '',
      lessons: []
    }
  }),
  mounted () {
    this.getLesson()
  },
  methods: {
    // get lesson
    async getLesson () {
      try {
        const { data } = await axios.get('/api/lesson/' + this.$route.params.slug)

        this.lesson = data
      } catch (e) {}
    }
  }
}
</script>
