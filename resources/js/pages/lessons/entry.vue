<template>
  <div class="row">
    <div class="col">
      <card :title="formTitle">
        <template v-slot>
          <form @submit.prevent="createLesson" @keydown="form.onKeydown($event)">
            <!-- Course -->
            <div class="form-group row">
              <label for="course_id" class="col-md-3 col-form-label text-md-right">Course</label>
              <div class="col-md-7">
                <select id="course_id" v-model="form.course_id" :disabled="course_id > 0" :class="{ 'is-invalid': form.errors.has('course_id') }" class="form-control" name="course_id">
                  <template v-for="(course, index) in courses">
                    <option :key="index" :value="index">
                      {{ course }}
                    </option>
                  </template>
                </select>
                <has-error :form="form" field="course_id" />
              </div>
            </div>
            <!-- Title -->
            <div class="form-group row">
              <label for="title" class="col-md-3 col-form-label text-md-right">Title</label>
              <div class="col-md-7">
                <input id="title" v-model="form.title" :class="{ 'is-invalid': form.errors.has('title') }" class="form-control" type="text" name="title">
                <has-error :form="form" field="title" />
              </div>
            </div>
            <!-- Excerpt -->
            <div class="form-group row">
              <label for="excerpt" class="col-md-3 col-form-label text-md-right">Excerpt</label>
              <div class="col-md-7">
                <textarea id="excerpt" v-model="form.excerpt" :class="{ 'is-invalid': form.errors.has('excerpt') }" class="form-control" name="excerpt" />
                <has-error :form="form" field="excerpt" />
              </div>
            </div>
            <!-- Content -->
            <div class="form-group row">
              <label for="content" class="col-md-3 col-form-label text-md-right">Content</label>
              <div class="col-md-7">
                <textarea id="content" v-model="form.content" :class="{ 'is-invalid': form.errors.has('content') }" rows="6" class="form-control" name="content" />
                <has-error :form="form" field="content" />
              </div>
            </div>
            <!-- Submit Button -->
            <div class="form-group row">
              <div class="col-md-9 ml-md-auto">
                <v-button :loading="form.busy" type="success">
                  {{ formTitle }}
                </v-button>
              </div>
            </div>
          </form>
        </template>
      </card>
    </div>
  </div>
</template>

<script>
import Form from 'vform'
import axios from 'axios'
import Swal from 'sweetalert2'

export default {
  name: 'LessonEntry',
  middleware: 'auth',
  metaInfo () {
    return { title: 'Lesson Entry' }
  },
  data: () => ({
    form: new Form({
      course_id: 0,
      title: '',
      excerpt: '',
      content: ''
    }),
    courses: []
  }),
  computed: {
    formTitle () {
      return this.$route.query.id ? 'Edit Lesson' : 'Create Lesson'
    },
    course_id () {
      return this.$route.params.id
    }
  },
  mounted () {
    this.getCourses()

    if (this.course_id > 0) {
      this.form.course_id = this.course_id
    }
    if (this.$route.query.id) {
      this.getLesson()
    }
  },
  methods: {
    async createLesson () {
      try {
        let message = 'success'
        if (this.$route.query.id) {
          const { data } = await this.form.patch('/api/admin/lesson/' + this.$route.query.id)
          message = data.message
        } else {
          const { data } = await this.form.post('/api/admin/lesson')
          message = data.message
        }

        Swal.fire({
          title: 'Success',
          type: 'success',
          text: message,
          showConfirmButton: false,
          timer: 1500
        }).then(() => this.$router.push('/dashboard/lessons'))
      } catch (err) {
        if (err.response.status === 422) {
          const { errors } = err.response.data
          const fields = Object.values(errors)
          let message = ''
          for (let i = 0; i < fields.length; i++) {
            for (let j = 0; j < fields[i].length; j++) {
              message += `<p class="text-danger">${fields[i][j]}</p>`
            }
          }
          Swal.fire({
            title: 'Error!',
            type: 'error',
            html: message
          })
        } else {
          Swal.fire({
            title: 'Error!',
            type: 'error',
            text: err.response.data.message
          })
        }
      }
    },
    // get lesson for edit
    async getLesson () {
      try {
        const { data } = await axios.get('/api/admin/lesson/' + this.$route.query.id + '/edit')

        this.form.keys().forEach(key => {
          this.form[key] = data[key]
        })
      } catch (e) {}
    },
    // get courses
    async getCourses () {
      try {
        const { data } = await axios.get('/api/admin/course-all')
        this.courses = data
      } catch (e) {}
    }
  }
}
</script>
