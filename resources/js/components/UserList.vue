<template>
  <div>
    <!-- Main leader board table -->
    <div class="container leader-board mt-5">
      <h2 class="mb-4">User Leaderboard</h2>

      <!-- Search and Filter Input -->
      <div class="input-group mb-3">
        <input v-model="searchQuery" type="text" class="form-control" placeholder="Search by name"
          aria-label="Search by name" id="search" @input="debouncedSearch" />
        <button class="btn btn-outline-secondary" @click="fetchUsers">Search</button>
      </div>

      <!-- Leaderboard Table -->
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th scope="col"></th>
            <th scope="col">
              <a href="#" @click.prevent="sortBy('name')">Name</a>
            </th>
            <th scope="col">
              <a href="#" @click.prevent="sortBy('points')">Points</a>
            </th>
          </tr>
        </thead>
        <tbody id="leaderboard">
          <tr v-for="user in users" :key="user.id">
            <td>
              <button class="btn btn-danger" @click="removeUser(user.id)">X</button>
            </td>
            <td class="name-column" @click="showUserDetails(user.id)">
              <span class="fw-bold">{{ user.name }}</span>
            </td>
            <td>
              <div class="d-flex justify-content-center align-items-center">
                <div class="btn-group">
                  <button class="btn btn-outline-primary" @click="updatePoint(user.id, 1, 'add')">+</button>
                  <button class="btn btn-outline-secondary" @click="updatePoint(user.id, 1, 'remove')">-</button>
                </div>
                <span class="ms-3">{{ user.points }} points</span>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Add User Button -->
      <button class="btn btn-outline-primary btn-add float-end mb-4" @click="openUserAddModal">+ Add User</button>
    </div>

    <!-- Modal for user details -->
    <div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header float-right">
            <h5>User Details</h5>
            <div class="text-right">
              <i data-dismiss="modal" aria-label="Close" class="fa fa-close"></i>
            </div>
          </div>
          <div class="modal-body">
            <div>
              <table class="table table-bordered">
                <tr>
                  <th>Name</th>
                  <td>{{ userDetails.name }}</td>
                </tr>
                <tr>
                  <th>Age</th>
                  <td>{{ userDetails.age }}</td>
                </tr>
                <tr>
                  <th>Points</th>
                  <td>{{ userDetails.points }}</td>
                </tr>
                <tr>
                  <th>Address</th>
                  <td>{{ userDetails.address }}</td>
                </tr>
              </table>

            </div>
          </div>
        </div>
      </div>
    </div>

     <!-- Modal for User Creation -->
    <div class="modal fade" id="userCreationModal" tabindex="-1" aria-labelledby="userCreationModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="userCreationModalLabel">Create New User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <UserCreationForm @user-created="handleUserCreated" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import debounce from 'lodash/debounce';
import { Modal } from 'bootstrap';
import UserCreationForm from '../components/UserCreationForm.vue';

export default {
  components: {
    UserCreationForm
  },
  data() {
    return {
      users: [],
      searchQuery: '',
      sortKey: 'points',
      sortOrder: 'desc',
      pager: {
        total: 0,
        pageSize: 25,
        recordsFiltered: -1,
        currentPage: 1
      },
      userDetails:{
        name: '',
        age: 0,
        points: 0,
        address: '',
      },
      showUserDetailsPopup: false,
      showUserCreatePopup: false,
    };
  },
  methods: {
    sortBy(key) {
      let vm = this;
      if (this.sortKey === key) {
        // Toggle sort order if the same key is clicked
        this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
      } else {
        // Set sort key and default to ascending order
        this.sortKey = key;
        this.sortOrder = 'asc';
      }
      // Fetch the sorted users
      vm.fetchUsers();
    },
    fetchUsers() {
      let vm = this;
      let parameters = {
        params: {
          'page_size': vm.pager.pageSize,
          'page_number': vm.pager.currentPage,
          'sort_key': vm.sortKey,
          'sort_order': vm.sortOrder,
          'name': vm.searchQuery,

        }
      };
      const response = axios.get('/api/users', parameters)
        .then(response => {
          this.users = response.data;
        })
        .catch(error => {
          console.error('There was an error fetching the users:', error);
        });
    },
    fetchUsersByScore() {
      axios.get('/api/users/grouped-by-score')
        .then(response => {
          this.users = response.data;
        })
        .catch(error => {
          console.error('There was an error fetching the users:', error);
        });
    },
    loadUsers() {
      let vm = this;
      vm.fetchUsers({});
    },
    removeUser(id) {
      let vm = this;
      const response = axios.delete('/api/users/' + id)
        .then(response => {
          vm.fetchUsers();
        })
        .catch(error => {
          console.error('There was an error deleting the users:', error);
        });
    },
    showUserDetails(id) {
      let vm = this;
      const response = axios.get('/api/users/' + id)
        .then(response => {
          vm.showUserDetailsPopup = true;
          vm.userDetails.name = response.data.user.name;
          vm.userDetails.age = response.data.user.age;
          vm.userDetails.points = response.data.user.points;
          vm.userDetails.address = response.data.address;
          const modal = new Modal(document.getElementById('userDetailsModal'));
          modal.show();
        })
        .catch(error => {
          console.error('There was an error showing user details:', error);
        });

    },
    updatePoint(userId, points, operation) {
      let vm = this;
      let parameters = {
        'points': points,
        'operation': operation,
      };

      const response = axios.patch('/api/users/' + userId + '/points', parameters)
        .then(response => {
          vm.fetchUsers();
        })
        .catch(error => {
          console.error('There was an error updating points:', error);
        });
    },
    openUserAddModal() {
      const modal = new Modal(document.getElementById('userCreationModal'));
      modal.show();
    },
    handleUserCreated(newUser) {
      this.fetchUsers(); // Refresh the user list after a new user is created
      const modal = Modal.getInstance(document.getElementById('userCreationModal'));
      modal.hide();
    },
  },
  mounted() {
    this.loadUsers();
    this.debouncedSearch = debounce(this.fetchUsers, 700);
  }
};
</script>

<style scoped>
.table td,
.table th {
  vertical-align: middle;
  text-align: center;
}

.input-group {
  margin-bottom: 20px;
}

.table tbody tr td.name-column {
    cursor: pointer;
}
</style>