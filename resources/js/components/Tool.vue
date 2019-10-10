<template>
    <div>
        <heading class="mb-6">Gitlog</heading>

        <card class="bg-white flex flex-col items-center justify-center p-4">
            <line-chart :options="options" :styles="chartStyle" :chart-data="chartData"></line-chart>
        </card>
        <card class="bg-white w-full mt-8">
            <div v-for="(commit, index) in history"
                 :class="index !== history.length -1 ? 'border-b' : ''"
                 class="border-90-half p-4 flex flex-row">
                <div class="flex items-center w-100">
                    <Gravatar class="rounded-full mr-4" :email="commit.email" :alt="commit.author" :size="32"
                              default-img="mm"></Gravatar>
                    <div class="text-md">
                        <p class="text-gray-900 leading-none mb-2">{{commit.author}}</p>
                        <p class="text-gray-600 text-sm">{{commit.date}}</p>
                    </div>
                </div>
                <div class="w-auto pl-8">
                    <p class="text-sm text-gray-600 flex items-center mb-2">
                        <span v-if="commit.message.includes('fix')">
                        <svg class="fill-current text-gray-500 w-3 h-3 mr-2"
                             xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 512 512">
                            <path
                                d="M15,422c-4.874,0-9.444,2.368-12.255,6.35c-2.811,3.982-3.511,9.082-1.879,13.674C15.556,483.345,55.94,512,101,512   c40.506,0,77.237-23.715,94.49-60h122.02c17.253,36.285,53.984,60,94.49,60c44.502,0,84.345-28.131,99.143-70.001   c1.623-4.591,0.916-9.684-1.896-13.66S501.869,422,497,422h-70v-30h70c4.869,0,9.436-2.363,12.247-6.339s3.519-9.068,1.896-13.66   C496.345,330.131,456.502,302,412,302c-18.247,0-35.72,4.826-51,13.432V271h75c8.284,0,15-6.716,15-15c0-8.284-6.716-15-15-15h-75   v-32.076c50.817-7.301,90-51.119,90-103.924V75c0-8.284-6.716-15-15-15s-15,6.716-15,15v30c0,37.25-27.301,68.231-62.943,74.018   c-4.69-12.3-14.623-22.027-27.057-26.438V135c0-19.049-7.151-36.452-18.894-49.695C340.627,74.68,361,47.178,361,15   c0-8.284-6.716-15-15-15s-15,6.716-15,15c0,24.813-20.187,45-45,45h-60c-24.813,0-45-20.187-45-45c0-8.284-6.716-15-15-15   s-15,6.716-15,15c0,32.178,20.373,59.68,48.894,70.305C188.151,98.548,181,115.951,181,135v17.58   c-12.435,4.41-22.367,14.137-27.057,26.438C118.301,173.232,91,142.25,91,105V75c0-8.284-6.716-15-15-15s-15,6.716-15,15v30   c0,52.804,39.183,96.623,90,103.924V241H76c-8.284,0-15,6.716-15,15c0,8.284,6.716,15,15,15h75v43.865   C135.961,306.609,118.848,302,101,302c-44.947,0-85.411,28.563-100.134,69.976c-1.632,4.592-0.932,9.692,1.879,13.674   C5.556,389.632,10.126,392,15,392h71v30H15z M40.133,452H86v28.471C67.791,476.752,51.332,466.451,40.133,452z M341.645,431.976   c-2.126-5.981-7.786-9.976-14.134-9.976H185.488c-6.348,0-12.008,3.995-14.134,9.976c-8.909,25.063-30.196,43.229-55.354,48.47   c0-32.22,0-113.316,0-146.892c25.158,5.24,46.445,23.406,55.354,48.47c2.126,5.981,7.786,9.976,14.134,9.976h142.023   c6.348,0,12.008-3.995,14.134-9.976c8.909-25.063,30.196-43.229,55.354-48.47c0,33.39,0,114.427,0,146.892   C371.842,475.206,350.555,457.04,341.645,431.976z M301,150h-90v-15c0-24.813,20.187-45,45-45s45,20.187,45,45V150z M316,180   c8.271,0,15,6.729,15,15v145.206c0,0.154,0.019,0.303,0.023,0.456c-5.283,6.475-9.843,13.619-13.513,21.338H271V180H316z    M472.127,452c-10.981,14.586-27.049,24.801-45.127,28.484V452H472.127z M472.127,362H427v-28.484   C445.078,337.199,461.146,347.414,472.127,362z M196,180h45v182h-45.51c-3.895-8.191-8.791-15.734-14.49-22.517V195   C181,186.729,187.728,180,196,180z M40.133,362C51.332,347.549,67.791,337.249,86,333.529V362H40.133z"/>
                        </svg>
                        </span>
                        {{commit.hash}}
                    </p>
                    <p class="text-gray-700 text-base">{{commit.message}}</p>
                </div>
            </div>
        </card>
    </div>
</template>

<script>
    import LineChart from './LineChart.js';
    import Gravatar  from 'vue-gravatar';

    export default {
        components: {
            LineChart,
            Gravatar
        },
        data: () => {
            return {
                apiURL: '/nova-vendor/gitlog/',
                history: [
                    {
                        author: '',
                        date: '',
                        hash: '',
                        message: ''
                    }
                ],
                chartStyle: {
                    width: '100%',
                    position: 'relative'
                },
                chartData: {
                    datasets: [
                        {
                            lineTension: 0.3,
                            borderColor: '#f44336',
                            backgroundColor: 'rgba(244,64,54,.1)',
                            label: 'Commits',
                            data: []
                        }
                    ]
                },
                options: {
                    legend: {
                        display: false
                    },
                    maintainAspectRatio: false,
                    responsive: true,
                    scales: {
                        xAxes: [
                            {
                                type: 'time',
                                position: 'bottom',
                                time: {
                                    unit: 'month'
                                }
                            }
                        ],
                        yAxes: [
                            {
                                ticks: {
                                    beginAtZero: true
                                }
                            }
                        ]
                    }
                }
            }
        },
        mounted() {
            this.getLog();
        },
        methods: {
            getLog() {
                let vm = this;
                Nova.request().get(this.apiURL + 'gitlog').then(response => {
                    vm.history = response.data.history;
                    let changelog_graph_chartData = response.data.dates,
                        counter;
                    for (counter = 0; counter < changelog_graph_chartData.length; counter += 1) {
                        vm.chartData.datasets[0].data.push(
                            {
                                x: new Date(changelog_graph_chartData[counter].date),
                                y: changelog_graph_chartData[counter].count
                            }
                        );
                    }
                });
            }
        }
    }
</script>

<style>
    /* Scoped Styles */
</style>
