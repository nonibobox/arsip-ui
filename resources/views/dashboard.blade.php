<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="shadow-lg rounded-lg overflow-hidden">
                        <div class="py-3 px-5 bg-gray-50">Arsip Statis</div>
                        <canvas class="p-10" id="chartBar"></canvas>
                    </div>

                    <!-- Required chart.js -->
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                    <!-- Chart bar -->
                    <script>
                        const labelsBarChart = [
                            "2014",
                            "2015",
                            "2016",
                            "2017",
                            "2018",
                            "2019",
                            "2020",
                            "2021",
                            "2022",
                        ];
                        const dataBarChart = {
                            labels: labelsBarChart,
                            datasets: [{
                                label: "Data Arsip Statis",
                                backgroundColor: "hsl(252, 82.9%, 67.8%)",
                                borderColor: "hsl(252, 82.9%, 67.8%)",
                                data: ["{{ $models['2014'] }}", "{{ $models['2015'] }}", "{{ $models['2016'] }}", "{{ $models['2017'] }}", "{{ $models['2018'] }}", "{{ $models['2019'] }}", "{{ $models['2020'] }}", "{{ $models['2021'] }}", "{{ $models['2022'] }}"],
                            }, ],
                        };

                        const configBarChart = {
                            type: "bar",
                            data: dataBarChart,
                            options: {},
                        };

                        var chartBar = new Chart(
                            document.getElementById("chartBar"),
                            configBarChart
                        );
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>