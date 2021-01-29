@extends('layouts.app')
@section('title', __('home.home'))
@php
$rtl = false ;
@endphp
@if( in_array(session()->get('user.language', config('app.locale')), config('constants.langs_rtl')) )
	@php
		$rtl = true ;
	@endphp
@endif
@section('content')

<!-- Content Header (Page header) -->
<section class="content-header content-header-custom" dir="rtl">
    <h1>
      {{ __('home.welcome_message', ['name' => Session::get('user.first_name')]) }}
    </h1>
</section>
<!-- Main content -->
<section class="content content-custom no-print">
  <br>
    @if(auth()->user()->can('dashboard.data'))
    	<div class="row">
            <div class="col-md-4 col-xs-12">
              @if(count($all_locations) > 1)
                {!! Form::select('dashboard_location', $all_locations, null, ['class' => 'form-control select2', 'placeholder' => __('lang_v1.select_location'), 'id' => 'dashboard_location']); !!}
              @endif
            </div>
    		{{-- <div class="col-md-8 col-xs-12">
    			<div class="btn-group pull-right" data-toggle="buttons">
    				<label class="btn btn-info active">
        				<input type="radio" name="date-filter"
        				data-start="{{ date('Y-m-d') }}" 
        				data-end="{{ date('Y-m-d') }}"
        				checked> {{ __('home.today') }}
      				</label>
      				<label class="btn btn-info">
        				<input type="radio" name="date-filter"
        				data-start="{{ $date_filters['this_week']['start']}}" 
        				data-end="{{ $date_filters['this_week']['end']}}"
        				> {{ __('home.this_week') }}
      				</label>
      				<label class="btn btn-info">
        				<input type="radio" name="date-filter"
        				data-start="{{ $date_filters['this_month']['start']}}" 
        				data-end="{{ $date_filters['this_month']['end']}}"
        				> {{ __('home.this_month') }}
      				</label>
      				<label class="btn btn-info">
        				<input type="radio" name="date-filter" 
        				data-start="{{ $date_filters['this_fy']['start']}}" 
        				data-end="{{ $date_filters['this_fy']['end']}}" 
        				> {{ __('home.this_fy') }}
      				</label>
                </div>
    		</div> --}}
    	</div>
    	<br>
      <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class=" @if($rtl) float-left  @else float-right @endif  mt-2">
                        {{-- <div id="total-revenue-chart"></div> --}}
                    </div>
                    <div>
                        {{-- <h4 class="mb-1 mt-1">$<span data-plugin="counterup">34,152</span></h4> --}}
                        <span class="info-box-number total_purchase"><i class="fas fa-sync fa-spin fa-fw margin-bottom"></i></span>
                        <p class="text-muted mb-0"> {{ __('home.total_purchase') }} </p>
                    </div>
                    {{-- //TODO calc this and uncomment it  --}}
                    {{-- <p class="text-muted mt-3 mb-0"><span class="text-success mr-1"><i class="mdi mdi-arrow-up-bold ml-1"></i>2.65%</span> since last week
                    </p> --}}
                </div>
            </div>
        </div> <!-- end col-->
    
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="@if($rtl) float-left  @else float-right @endif  mt-2">
                        <div id="orders-chart"> </div>
                    </div>
                    <div>
                        <span class="info-box-number total_sell"><i class="fas fa-sync fa-spin fa-fw margin-bottom"></i></span>
                        <p class="text-muted mb-0">{{ __('home.total_sell') }}</p>
                    </div>
                    {{-- <p class="text-muted mt-3 mb-0"><span class="text-danger mr-1"><i class="mdi mdi-arrow-down-bold ml-1"></i>0.82%</span> since last week
                    </p> --}}
                </div>
            </div>
        </div> <!-- end col-->
    
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="@if($rtl) float-left  @else float-right @endif  mt-2">
                        {{-- <div id="customers-chart"> </div> --}}
                    </div>
                    <div>
                      <span class="info-box-number purchase_due"><i class="fas fa-sync fa-spin fa-fw margin-bottom"></i></span>
                      <span class="info-box-text">{{ __('home.purchase_due') }}</span>
                    </div>
                    {{-- <p class="text-muted mt-3 mb-0"><span class="text-danger mr-1"><i class="mdi mdi-arrow-down-bold ml-1"></i>6.24%</span> since last week
                    </p> --}}
                </div>
            </div>
        </div> <!-- end col-->
    
        <div class="col-md-3">
    
            <div class="card">
                <div class="card-body">
                    <div class="@if($rtl) float-left  @else float-right @endif  mt-2">
                        {{-- <div id="growth-chart"></div> --}}
                    </div>
                    <div>
                      <span class="info-box-number invoice_due"><i class="fas fa-sync fa-spin fa-fw margin-bottom"></i></span>
                      <span class="info-box-text">{{ __('home.invoice_due') }}</span>
                    </div>
                    {{-- <p class="text-muted mt-3 mb-0"><span class="text-success mr-1"><i class="mdi mdi-arrow-up-bold ml-1"></i>10.51%</span> since last week
                    </p> --}}
                </div>
            </div>
        </div> <!-- end col-->
      </div> <!-- end row-->
        

      	<div class="row row-custom">
            <!-- expense -->
            <div class="col-md-3">
    
              <div class="card">
                  <div class="card-body">
                      <div class="@if($rtl) float-left  @else float-right @endif  mt-2">
                          {{-- <div id="expense-chart"></div> --}}
                      </div>
                      <div>
                        <span class="info-box-number total_expense"><i class="fas fa-sync fa-spin fa-fw margin-bottom"></i></span>
                        <span class="info-box-text">{{ __('lang_v1.expense') }}</span>
                      </div>
                      {{-- <p class="text-muted mt-3 mb-0"><span class="text-success mr-1"><i class="mdi mdi-arrow-up-bold ml-1"></i>10.51%</span> since last week
                      </p> --}}
                  </div>
              </div>
          </div> <!-- end col-->
            {{-- <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
              <div class="info-box info-box-new-style">
                <span class="info-box-icon bg-red">
                  <i class="fas fa-minus-circle"></i>
                </span>

                <div class="info-box-content">
                  <span class="info-box-text">
                    {{ __('lang_v1.expense') }}
                  </span>
                  <span class="info-box-number total_expense"><i class="fas fa-sync fa-spin fa-fw margin-bottom"></i></span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div> --}}
        </div>
        @if(!empty($widgets['after_sale_purchase_totals']))
            @foreach($widgets['after_sale_purchase_totals'] as $widget)
                {!! $widget !!}
            @endforeach
        @endif
        @if(!empty($all_locations))
          	<!-- sales chart start -->
          	<div class="row">
          		<div class="col-sm-12 m-auto" style="float: unset;">
                    {{-- @component('components.widget', ['class' => 'box-primary', 'title' => __('home.sells_last_30_days')])
                      {!! $sells_chart_1->container() !!}
                    @endcomponent --}}
                  <div class="chart">
                    <h4> {{ __('home.sells_last_30_days') }} </h4>
                    <div id="sells_last_30_days" class="apexcharts" style="max-width: 100%;"></div>
                  </div>
          		</div>
          	</div>
        @endif
        @if(!empty($widgets['after_sales_last_30_days']))
            @foreach($widgets['after_sales_last_30_days'] as $widget)
                {!! $widget !!}
            @endforeach
        @endif
        @if(!empty($all_locations))
          	<div class="row">
          		<div class="col-sm-12">
                    {{-- @component('components.widget', ['class' => 'box-primary', 'title' => __('home.sells_current_fy')])
                      {!! $sells_chart_2->container() !!}
                    @endcomponent --}}
                    <div class="chart">
                      <h4> {{ __('home.sells_current_fy') }} </h4>
                      <div id="sells_current_fy" class="apexcharts" style="max-width: 100%;"></div>
                    </div>
          		</div>
          	</div>
        @endif
      	<!-- sales chart end -->
        @if(!empty($widgets['after_sales_current_fy']))
            @foreach($widgets['after_sales_current_fy'] as $widget)
                {!! $widget !!}
            @endforeach
        @endif
      	<!-- products less than alert quntity -->
      	<div class="row">
            <div class="col-sm-6">
                @component('components.widget', ['class' => 'box-warning'])
                  @slot('icon')
                    <i class="fa fa-exclamation-triangle text-yellow" aria-hidden="true"></i>
                  @endslot
                  @slot('title')
                    {{ __('lang_v1.sales_payment_dues') }} @show_tooltip(__('lang_v1.tooltip_sales_payment_dues'))
                  @endslot
                  <table class="table table-bordered table-striped" id="sales_payment_dues_table">
                    <thead>
                      <tr>
                        <th>@lang( 'contact.customer' )</th>
                        <th>@lang( 'sale.invoice_no' )</th>
                        <th>@lang( 'home.due_amount' )</th>
                      </tr>
                    </thead>
                  </table>
                @endcomponent
            </div>
            <div class="col-sm-6">
                @component('components.widget', ['class' => 'box-warning'])
                @slot('icon')
                <i class="fa fa-exclamation-triangle text-yellow" aria-hidden="true"></i>
                @endslot
                @slot('title')
                {{ __('lang_v1.purchase_payment_dues') }} @show_tooltip(__('tooltip.payment_dues'))
                @endslot
                <table class="table table-bordered table-striped" id="purchase_payment_dues_table">
                    <thead>
                      <tr>
                        <th>@lang( 'purchase.supplier' )</th>
                        <th>@lang( 'purchase.ref_no' )</th>
                        <th>@lang( 'home.due_amount' )</th>
                      </tr>
                    </thead>
                </table>
                @endcomponent
            </div>
        </div>
        <div class="row">
            <div class="@if((session('business.enable_product_expiry') != 1) && auth()->user()->can('stock_report.view')) col-sm-12 @else col-sm-6 @endif">
                @component('components.widget', ['class' => 'box-warning'])
                  @slot('icon')
                    <i class="fa fa-exclamation-triangle text-yellow" aria-hidden="true"></i>
                  @endslot
                  @slot('title')
                    {{ __('home.product_stock_alert') }} @show_tooltip(__('tooltip.product_stock_alert'))
                  @endslot
                  <table class="table table-bordered table-striped" id="stock_alert_table">
                    <thead>
                      <tr>
                        <th>@lang( 'sale.product' )</th>
                        <th>@lang( 'business.location' )</th>
                        <th>@lang( 'report.current_stock' )</th>
                      </tr>
                    </thead>
                  </table>
                @endcomponent
            </div>
            @can('stock_report.view')
                @if(session('business.enable_product_expiry') == 1)
                  <div class="col-sm-6">
                      @component('components.widget', ['class' => 'box-warning'])
                          @slot('icon')
                            <i class="fa fa-exclamation-triangle text-yellow" aria-hidden="true"></i>
                          @endslot
                          @slot('title')
                            {{ __('home.stock_expiry_alert') }} @show_tooltip( __('tooltip.stock_expiry_alert', [ 'days' =>session('business.stock_expiry_alert_days', 30) ]) )
                          @endslot
                          <input type="hidden" id="stock_expiry_alert_days" value="{{ \Carbon::now()->addDays(session('business.stock_expiry_alert_days', 30))->format('Y-m-d') }}">
                          <table class="table table-bordered table-striped" id="stock_expiry_alert_table">
                            <thead>
                              <tr>
                                  <th>@lang('business.product')</th>
                                  <th>@lang('business.location')</th>
                                  <th>@lang('report.stock_left')</th>
                                  <th>@lang('product.expires_in')</th>
                              </tr>
                            </thead>
                          </table>
                      @endcomponent
                  </div>
                @endif
            @endcan
      	</div>
        @if(!empty($widgets['after_dashboard_reports']))
          @foreach($widgets['after_dashboard_reports'] as $widget)
            {!! $widget !!}
          @endforeach
        @endif
    @endif
</section>
<!-- /.content -->
@stop
@section('javascript')
    <script src="{{ asset('js/home.js?v=' . $asset_v) }}"></script>
    @if(!empty($all_locations))
        {!! $sells_chart_1->script() !!}
        {!! $sells_chart_2->script() !!}
    @endif

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
      var sell_values = {{ $all_sell_values }} ;
      var options = {
        chart: {
          type: 'area',
          height:' 500px '
        },
        series: [{
          name: 'sales',
          data: sell_values
        }],
        xaxis: {
          categories: {!! $sell_values_months !!}
        },
        dataLabels: {
          enabled: false
        },
        stroke:{
          curve:'smooth'
        },
        colors:['#2bd982']
      }

      var chart = new ApexCharts(document.querySelector("#sells_last_30_days"), options);
      chart.render();



      var options1 = {
        chart: {
          type: 'area',
          height:' 500px '
        },
        series: [{
          name: 'sales',
          data: [25, 66, 41, 89, 63, 25, 44, 12, 36, 9, 54, 100]
        }],
        xaxis: {
          categories: {!! $fy_labels !!}
        },
        dataLabels: {
          enabled: false
        },
        stroke:{
          curve:'smooth'
        },
        colors:['#8E24AA']
      }
      var chart1 = new ApexCharts(document.querySelector("#sells_current_fy"), options1);

      
      chart1.render();
    </script>

    {{-- charts --}}
    <script>


      //
      // Orders Chart
      //
    //   var options = {
    //     series: [{
    //           data: [25, 66, 41, 89, 63, 25, 44, 12, 36, 9, 54]
    //       }],
    //       fill: {
    //           colors: ['#f1b44c']
    //       },
    //       chart: {
    //           type: 'bar',
    //           width: 70,
    //           height: 40,
    //           sparkline: {
    //               enabled: true
    //           }
    //       },
    //       plotOptions: {
    //           bar: {
    //               columnWidth: '50%'
    //           }
    //       },
    //       labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
    //       xaxis: {
    //           crosshairs: {
    //               width: 1
    //           },
    //       },
    //       tooltip: {
    //           fixed: {
    //               enabled: false
    //           },
    //           x: {
    //               show: false
    //           },
    //           y: {
    //               title: {
    //                   formatter: function (seriesName) {
    //                       return ''
    //                   }
    //               }
    //           },
    //           marker: {
    //               show: false
    //           }
    //       }
    //   };

    //   var chart = new ApexCharts(document.querySelector("#total-revenue-chart"), options);
    //   chart.render();
      //
      // Total Revenue Chart
      //
      var options1 = {
          series: [{
              data: sell_values.slice(Math.max(sell_values.length - 7  , 0 ))
          }],
          fill: {
              colors: ['#5b73e8']
          },
          chart: {
              type: 'bar',
              width: 70,
              height: 40,
              sparkline: {
                  enabled: true
              }
          },
          dataLabels: {
            position: 'bottom'
          },
          plotOptions: {
              bar: {
                  columnWidth: '50%'
              }
          },
          labels: [1, 2, 3, 4, 5, 6, 7],
          xaxis: {
              crosshairs: {
                  width: 1
              },
          },
          tooltip: {
              fixed: {
                  enabled: false
              },
              x: {
                  show: false
              },
              y: {
                  title: {
                      formatter: function (seriesName) {
                          return ''
                      }
                  }
              },
              marker: {
                  show: true
              }
          }
      };
      
      var chart1 = new ApexCharts(document.querySelector("#orders-chart"), options1);
      chart1.render();

      //
      // Customers Chart
      //

    //   var options = {
    //     series: [{
    //           data: [25, 66, 41, 89, 63, 25, 44, 12, 36, 9, 54]
    //       }],
    //       fill: {
    //           colors: ['#34c38f']
    //       },
    //       chart: {
    //           type: 'bar',
    //           width: 70,
    //           height: 40,
    //           sparkline: {
    //               enabled: true
    //           }
    //       },
    //       plotOptions: {
    //           bar: {
    //               columnWidth: '50%'
    //           }
    //       },
    //       labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
    //       xaxis: {
    //           crosshairs: {
    //               width: 1
    //           },
    //       },
    //       tooltip: {
    //           fixed: {
    //               enabled: false
    //           },
    //           x: {
    //               show: false
    //           },
    //           y: {
    //               title: {
    //                   formatter: function (seriesName) {
    //                       return ''
    //                   }
    //               }
    //           },
    //           marker: {
    //               show: false
    //           }
    //       }
    //   };

    //   var chart = new ApexCharts(document.querySelector("#customers-chart"), options);
    //   chart.render();


      //
      // Growth Chart
      //
    //   var options2 = {
    //       series: [{
    //           data: [25, 66, 41, 89, 63, 25, 44, 12, 36, 9, 54]
    //       }],
    //       fill: {
    //           colors: ['#f46a6a']
    //       },
    //       chart: {
    //           type: 'bar',
    //           width: 70,
    //           height: 40,
    //           sparkline: {
    //               enabled: true
    //           }
    //       },
    //       plotOptions: {
    //           bar: {
    //               columnWidth: '50%'
    //           }
    //       },
    //       labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
    //       xaxis: {
    //           crosshairs: {
    //               width: 1
    //           },
    //       },
    //       tooltip: {
    //           fixed: {
    //               enabled: false
    //           },
    //           x: {
    //               show: false
    //           },
    //           y: {
    //               title: {
    //                   formatter: function (seriesName) {
    //                       return ''
    //                   }
    //               }
    //           },
    //           marker: {
    //               show: false
    //           }
    //       }
    //   };

    //   var chart2 = new ApexCharts(document.querySelector("#growth-chart"), options2);
    //   chart2.render();

            //
      // Growth Chart
      //
    //   var options3 = {
    //       series: [{
    //           data: [25, 66, 41, 89, 63, 25, 44]
    //       }],
    //       fill: {
    //           colors: ['#9C27B0']
    //       },
    //       chart: {
    //           type: 'bar',
    //           width: 70,
    //           height: 40,
    //           sparkline: {
    //               enabled: true
    //           }
    //       },
    //       plotOptions: {
    //           bar: {
    //               columnWidth: '50%'
    //           }
    //       },
    //       labels: [1, 2, 3, 4, 5, 6, 7],
    //       xaxis: {
    //           crosshairs: {
    //               width: 1
    //           },
    //       },
    //       tooltip: {
    //           fixed: {
    //               enabled: false
    //           },
    //           x: {
    //               show: false
    //           },
    //           y: {
    //               title: {
    //                   formatter: function (seriesName) {
    //                       return ''
    //                   }
    //               }
    //           },
    //           marker: {
    //               show: false
    //           }
    //       }
    //   };

    //   var chart2 = new ApexCharts(document.querySelector("#expense-chart"), options3);
    //   chart2.render();

    </script>
@endsection

