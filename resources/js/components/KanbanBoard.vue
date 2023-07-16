<template>
    <div class="container py-4">
        <button class="btn btn-primary" @click="showCreateTaskModal">Create Task</button>
        <div class="row justify-content-center">
            <div class="col-md-4" v-for="status in statuses" :key="status">
                <h2>{{ status }}</h2>
                <div class="card bg-light shadow mb-3">
                    <div class="card-header drag-handle">{{ status }}</div>
                    <draggable
                        :list="getTasksByStatus(status)"
                        group="tasks"
                        @end="onDragEnd"
                        drag-handle=".drag-handle"
                    >
                        <div class="card-body task draggable-item" v-for="task in getTasksByStatus(status)" :data-id="task.id">
                            <div class="drag-handle"></div>
                            <h5 class="card-title">{{ task.title }}</h5>
                            <p class="card-text">{{ task.description }}</p>
                            <p class="card-text">{{ task.due_date }}</p>
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
                        <form @submit.prevent="updateTask">
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
                                        <option v-for="status in statuses" :value="status" :key="status">
                                            {{ status }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" @click="updateTask">Update Task</button>
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
                            <!-- Create Task Form goes here -->
                            <form @submit.prevent="createTask">
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
                                    <input type="text" class="form-control" v-model="newTask.status" required>
                                </div>
                                <!-- You can add more fields as needed -->
                                <button type="submit" class="btn btn-primary">Create</button>
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
    import Vue from 'vue';
    import axios from 'axios';

    export default {
        components: {
            draggable,
        },
        data() {
            return {
                tasks: [],
                statuses: [1, 2, 3],
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
            };
        },
        methods: {
            async fetchTasks() {
                const response = await axios.get('/api/tasks');
                this.tasks = response.data;
                console.log(this.tasks)
            },
            getTasksByStatus(status) {
                return this.tasks.filter((task) => task.status === status);
            },
            onDragStart(evt) {
                evt.item.classList.add('grabbing');
            },
            async onDragEnd(evt) {
                const taskId = Number(evt.item.getAttribute('data-id'));
                const newStatus = evt.to.parentElement.children[0].textContent;

                try {
                    const response = await axios.put(`/api/tasks/${taskId}`, { status: newStatus });

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
                console.log('here')
                try {
                    const response = await axios.put(`/api/tasks/${this.editedTask.id}`, this.editedTask);
                    const updatedTask = response.data;

                    const taskIndex = this.tasks.findIndex((task) => task.id === updatedTask.id);
                    if (taskIndex !== -1) {
                        this.tasks.splice(taskIndex, 1, updatedTask);
                    }

                    this.closeUpdateForm();
                } catch (error) {
                    console.error('Error updating task:', error);
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
            },
            async deleteTask(taskId) {
                try {
                    await axios.delete(`/api/tasks/${taskId}`);
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
                    // Send a POST request to create a new task
                    const response = await axios.post('/api/tasks', this.newTask);
                    const createdTask = response.data;
                    // Add the new task to the frontend
                    this.tasks.push(createdTask);
                    // Close the modal and reset the newTask object
                    this.closeCreateTaskModal();

                    // Fetch the updated task list from the server
                    await this.fetchTasks();
                } catch (error) {
                    console.error('Error creating task:', error);
                }
            },

            resetNewTask() {
                this.newTask = {
                    title: '',
                    description: '',
                    due_date: '',
                    status: '',
                };
            },
        },
        created() {
            this.fetchTasks();
        }
    };
</script>

<style>
    .draggable-item {
        cursor: pointer;
    }
    .drag-handle {
        cursor: grabbing;
        display: inline-block;
        margin-right: 8px;
        user-select: none;
    }
    .drag-handle:active {
        cursor: grabbing;
    }
</style>