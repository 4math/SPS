# Frontend part for SPS project

## Project setup
```
npm install
```

## Copy environmental variables
```
cp .env.example .env
cp .env.production.example .env.production
```

## Compiles and hot-reloads for development
```
npm run serve
```

## Compiles and minifies for production
```
npm run build
```

## Lints and fixes files
```
npm run lint
```

## Customize configuration
See [Configuration Reference](https://cli.vuejs.org/config/).

## Project's structure
- `assets` folder - contains images and styles
- `components` folder - contains Vue.js components:
  - `auth` folder:
    - `Login.vue` - login page.
    - `Register.vue` - register page.
  - `chart` folder:
    - `statistics-formatter` folder - contains Date formatters for different charts.
    - `BarChart.js` and `LineChart.js` - Chart.js' graph entities.
    - `ChartComponent.vue` - a card which shows a chart.
    - `ChartsPage.vue` - a page for chart display, also sends requests for chart data to API server or connects to the Websocket server for real-time data.
  - `modals` folder - contains modals for information input.
  - `Dashboard.vue` - a page where Smart Power Sockets can be shown, edited and controlled.
  - `HomePage.vue` - home page with general information.
  - `Navigator.vue` - header at the top where navigation happens.
  - `Socket.vue` - a card by which a specific Smart Power Socket can be controlled and shown.
- `consts` folder - contains constants.
- `objects` folder - contains models for incapsulating the data. 
- `router` folder - contains Vue router for navigation between Vue components. 
- `services/interceptors` folder - defines the actions when comes response from server and when request from client is sent. 
- `store` folder:
  - `actions` folder - describes actions which Vuex can dispatch and commit.
  - `modules` folder - describes getters, mutations and actions for Vuex.
- `App.vue` - web application's entry point.
- `main.js` - entry point for starting Vue.js.