<template>
  <div class="row">
    <div class="col">
      <card title="Courses">
        <template v-slot:action>
          <router-link to="/dashboard/courses/entry" class="btn btn-primary">
            <fa icon="plus" /> Create
          </router-link>
        </template>
        <template v-if="courses.length" v-slot>
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">
                  #
                </th>
                <th scope="col">
                  Title
                </th>
                <th scope="col">
                  Author
                </th>
                <th scope="col">
                  Lessons
                </th>
                <th scope="col">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(course, index) in courses" :key="course.id">
                <th scope="row">
                  {{ index + 1 }}
                </th>
                <td>{{ course.title }}</td>
                <td>{{ course.author }}</td>
                <td>{{ course.lessons_count }}</td>
                <td>
                  <router-link title="Add Lesson" :to="`/dashboard/courses/${course.id}/lessons/entry`" class="btn btn-info btn-sm">
                    <fa icon="pen" />
                  </router-link>
                  <router-link title="View" :to="`/dashboard/courses/${course.id}`" class="btn btn-success btn-sm">
                    <fa icon="eye" />
                  </router-link>
                  <router-link title="Edit" :to="`/dashboard/courses/entry?id=${course.id}`" class="btn btn-primary btn-sm">
                    <fa icon="pen" />
                  </router-link>
                  <button title="Delete" type="button" class="btn btn-danger btn-sm" @click="deleteItem(course)">
                    <fa icon="trash" />
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </template>
        <template v-else v-slot>
          <p>No Course Found!</p>
        </template>
      </card>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Swal from 'sweetalert2'

export default {
  name: 'Course',
  middleware: 'auth',
  metaInfo () {
    return { title: 'Course' }
  },
  data: () => ({
    courses: []
  }),
  mounted () {
    this.getCourses()
  },
  methods: {
    // get courses
    async getCourses () {
      try {
        const { data } = await axios.get('/api/admin/course')
        this.courses = data.data
      } catch (e) {}
    },
    // delete item
    deleteItem (item) {
      console.log(item)
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
            this.deleteCourse(item.id)
          }
          return false
        })
    },
    async deleteCourse (id) {
      try {
        const { data } = await axios.delete(
          `/api/admin/course/${id}`
        )
        Swal.fire({
          title: 'success',
          type: 'success',
          text: `${data.message}`
        })
        this.getCourses()
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
