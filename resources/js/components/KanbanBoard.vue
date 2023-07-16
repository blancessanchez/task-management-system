<template>
    <div class="container py-4">
        <div class="row pb-4">
            <button class="btn btn-primary" @click="showCreateTaskModal">Create Task</button>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-4" v-for="status in statuses" :key="status.id">
                <div class="card bg-light shadow mb-3">
                    <div class="card-header" :data-id="status.id">{{ status.text }}</div>
                    <draggable
                        :list="getTasksByStatus(status.id)"
                        group="tasks"
                        @end="onDragEnd"
                        drag-handle=".drag-handle"
                    >
                        <div
                            class="card-body task draggable-item"
                            v-for="task in getTasksByStatus(status.id)"
                            :data-id="task.id"
                        >
                            <div class="drag-handle"></div>
                            <h5 class="card-title">{{ task.title }}</h5>
                            <p class="card-text">{{ task.description }}</p>
                            <p class="card-text"><b>Due date:</b> {{ task.due_date }}</p>
                            <div class="d-flex justify-content-between mt-3">
                                <button class="btn btn-primary" @click="openUpdateForm(task)">Edit</button>
                                <button class="btn btn-danger" @click="confirmDelete(task.id)">Delete</button>
                            </div>
                        </div>
                    </draggable>
                </div>
            </div>
            <div v-if="isUpdateFormOpen" class="modal" tabindex="-1" role="dialog" style="display: block;">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form @submit.prevent="updateTask" novalidate>
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Task</h5>
                                <button
                                    type="button"
                                    class="close"
                                    @click="closeUpdateForm"
                                >
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div v-if="formErrors.length > 0" class="alert alert-danger">
                                    <ul>
                                        <li v-for="error in formErrors" :key="error">{{ error }}</li>
                                    </ul>
                                </div>
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input
                                        v-model="editedTask.title"
                                        type="text"
                                        id="title"
                                        class="form-control"
                                        required
                                    >
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea
                                        v-model="editedTask.description"
                                        id="description"
                                        rows="4"
                                        class="form-control"
                                    ></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="due_date">Due Date</label>
                                    <input
                                        v-model="editedTask.due_date"
                                        type="date"
                                        id="due_date"
                                        class="form-control"
                                    >
                                </div>
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select
                                        v-model="editedTask.status"
                                        id="status"
                                        class="form-control"
                                    >
                                        <option value="">-- Select status --</option>
                                        <option value="1">Todo</option>
                                        <option value="2">In Progress</option>
                                        <option value="3">Done</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success" @click="updateTask">Update Task</button>
                                <button type="button" class="btn btn-secondary" @click="closeUpdateForm">
                                    Close
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal" :class="{ 'd-block': isCreateTaskModalOpen }">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Create New Task</h5>
                            <button type="button" class="close" @click="closeCreateTaskModal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div v-if="formErrors.length > 0" class="alert alert-danger">
                                <ul>
                                    <li v-for="error in formErrors" :key="error">{{ error }}</li>
                                </ul>
                            </div>
                            <form @submit.prevent="createTask" novalidate>
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" v-model="newTask.title" required>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" v-model="newTask.description" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="due_date">Due Date</label>
                                    <input type="date" class="form-control" v-model="newTask.due_date">
                                </div>
                                <div class="form-group">
                                    <label for="title">Status</label>
                                    <select
                                        v-model="newTask.status"
                                        id="status"
                                        class="form-control"
                                    >
                                        <option value="">-- Select status --</option>
                                        <option value="1">Todo</option>
                                        <option value="2">In Progress</option>
                                        <option value="3">Done</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-success">Create</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import draggable from 'vuedraggable';
    import axios from 'axios';

    export default {
        props: ['apiKey'],
        components: {
            draggable,
        },
        data() {
            return {
                tasks: [],
                statuses: [
                    {
                        id: 1,
                        text: 'Todo'
                    },
                    {
                        id: 2,
                        text: 'In Progress'
                    },
                    {
                        id: 3,
                        text: 'Done'
                    },
                ],
                isUpdateFormOpen: false,
                editedTask: {
                    id: null,
                    title: '',
                    description: '',
                    due_date: '',
                    status: '',
                },
                isCreateTaskModalOpen: false,
                newTask: {
                    title: '',
                    description: '',
                    due_date: '',
                    status: '',
                },
                formErrors: [],
                headers: {
                    'X-API-Key': this.apiKey,
                }
            };
        },
        methods: {
            async fetchTasks() {
                const response = await axios.get('/api/tasks', { headers: this.headers });
                this.tasks = response.data;
            },
            getTasksByStatus(status) {
                return this.tasks.filter((task) => task.status === status);
            },
            onDragStart(evt) {
                evt.item.classList.add('grabbing');
            },
            async onDragEnd(evt) {
                const taskId = Number(evt.item.getAttribute('data-id'));
                const newStatus = evt.to.parentElement.children[0].dataset.id;
                try {
                    const response = await axios.put(`/api/task-status/${taskId}`, { id: Number(taskId), status: Number(newStatus) }, { headers: this.headers });

                    const updatedTask = this.tasks.find((task) => task.id === taskId);
                    updatedTask.status = Number(newStatus);
                } catch (error) {
                    console.error('Error updating task status:', error);
                }
            },
            openUpdateForm(task) {
                this.editedTask = { ...task };
                this.isUpdateFormOpen = true;
            },
            async updateTask() {
                try {
                    const response = await axios.put(`/api/tasks/${this.editedTask.id}`, this.editedTask, { headers: this.headers });
                    const updatedTask = response.data;
                    const taskIndex = this.tasks.findIndex((task) => task.id === updatedTask.id);
                    if (taskIndex !== -1) {
                        this.tasks.splice(taskIndex, 1, updatedTask);
                    }

                    this.closeUpdateForm();
                    this.formErrors = [];
                    await this.fetchTasks();
                } catch (error) {
                    if (error.response && error.response.status === 422) {
                        this.formErrors = Object.values(error.response.data.errors).flat();
                    } else {
                        console.error('Error creating task:', error);
                    }
                }
            },
            closeUpdateForm() {
                this.isUpdateFormOpen = false;
                this.editedTask = {
                    id: null,
                    title: '',
                    description: '',
                    due_date: '',
                    status: '',
                };
                this.formErrors = [];
            },
            async deleteTask(taskId) {
                try {
                    await axios.delete(`/api/tasks/${taskId}`, { headers: this.headers });
                    // Remove the deleted task from the frontend
                    this.tasks = this.tasks.filter((task) => task.id !== taskId);
                } catch (error) {
                    console.error('Error deleting task:', error);
                }
            },
            confirmDelete(taskId) {
                if (confirm('Are you sure you want to delete this task?')) {
                    this.deleteTask(taskId);
                }
            },
            showCreateTaskModal() {
                this.isCreateTaskModalOpen = true;
            },
            closeCreateTaskModal() {
                this.isCreateTaskModalOpen = false;
                this.resetNewTask();
            },
            async createTask() {
                try {
                    const response = await axios.post('/api/tasks', this.newTask, { headers: this.headers });
                    const createdTask = response.data;

                    this.tasks.push(createdTask);

                    this.closeCreateTaskModal();

                    await this.fetchTasks();
                    this.formErrors = [];
                } catch (error) {
                    if (error.response && error.response.status === 422) {
                        this.formErrors = Object.values(error.response.data.errors).flat();
                    } else {
                        console.error('Error creating task:', error);
                    }
                }
            },
            resetNewTask() {
                this.newTask = {
                    title: '',
                    description: '',
                    due_date: '',
                    status: '',
                };
                this.formErrors = [];
            },
        },
        created() {
            this.fetchTasks();
        }
    };
</script>
