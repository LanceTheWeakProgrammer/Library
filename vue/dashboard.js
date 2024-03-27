// dashboard.js

new Vue({
  el: '#app',
  data: {
      loading: true,
      error: false,
      errorMessage: '',
      dashboardData: {
          userProfile: {} 
      },
  },
  mounted() {
      this.fetchDashboardData();
  },
  methods: {
      fetchDashboardData() {
          fetch('../api/dashboardHandler.php?action=getDashboardData')
              .then(response => response.json())
              .then(data => {
                  if (data.error) {
                      this.error = true;
                      this.errorMessage = data.message;
                  } else {
                      this.dashboardData = data.data;
                  }
              })
              .catch(error => {
                  this.error = true;
                  this.errorMessage = 'Error fetching data';
                  console.error(error);
              })
              .finally(() => {
                  this.loading = false;
              });
      },
  },
});
