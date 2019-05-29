<template>
  <div class="row">
    <div class="col">
      <card title="Lessons">
        <template v-slot:action>
          <router-link to="/dashboard/lessons/entry" class="btn btn-primary">
            <fa icon="plus" /> Create
          </router-link>
        </template>
        <template v-if="lessons.length" v-slot>
          <table class="table table-striped table-responsive">
            <thead>
              <tr>
                <th scope="col">
                  #
                </th>
                <th scope="col">
                  Title
                </th>
                <th scope="col">
                  Course
                </th>
                <th scope="col" style="width: 115px;">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(lesson, index) in lessons" :key="lesson.id">
                <th scope="row">
                  {{ index + 1 }}
                </th>
                <td>{{ lesson.title }}</td>
                <td>
                  <router-link :to="`/dashboard/courses/${lesson.course_id}`">
                    {{ lesson.course_title }}
                  </router-link>
                </td>
                <td>
                  <router-link :to="`/dashboard/lessons/entry?id=${lesson.id}`" class="btn btn-primary btn-sm">
                    <fa icon="pen" />
                  </router-link>
                  <button type="button" class="btn btn-danger btn-sm" @click="deleteItem(lesson)">
                    <fa icon="trash" />
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </template>
        <template v-else v-slot>
          <p>No Lesson Found!</p>
        </template>
      </card>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Swal from 'sweetalert2'

export default {
  name: 'Lesson',
  middleware: 'auth',
  metaInfo () {
    return { title: 'Lesson' }
  },
  data: () => ({
    lessons: []
  }),
  mounted () {
    this.getLessons()
  },
  methods: {
    // get lessons
    async getLessons () {
      try {
        const { data } = await axios.get('/api/admin/lesson')
        this.lessons = data.data
      } catch (e) {}
    },
    // delete item
    deleteItem (item) {
      Swal
        .fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        })
        .then(result => {
          if (result.value) {
            this.deleteLesson(item.id)
          }
          return false
        })
    },
    async deleteLesson (id) {
      try {
        const { data } = await axios.delete(
          `/api/admin/lesson/${id}`
        )
        Swal.fire({
          title: 'success',
          type: 'success',
          text: `${data.message}`
        })
        this.getLessons()
      } catch (e) {
        Swal.fire({
          title: 'Error!',
          type: 'warning',
          text: `${e.response.data.message}`
        })
      }
    }
  }
}
</script>
