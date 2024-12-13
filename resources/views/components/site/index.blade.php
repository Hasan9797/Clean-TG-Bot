<?php

use App\Enums\UserStatusEnum;
?>

<x-layouts.main>
    <x-slot:title>
        Dashboard
    </x-slot>

    <div id="page-wrapper">
        <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <h2>Статистика</h2>
                    <h5>Все заказы:</h5>
                </div>
            </div>
            <!-- /. ROW  -->
            <hr />
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="panel panel-back noti-box">
                        <span class="icon-box bg-color-red set-icon">
                            <i class="fa fa-envelope-o"></i>
                        </span>
                        <div class="text-box">
                            <p class="main-text">{{ $count }}
                                New</p>
                            <p class="text-muted">Messages</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="panel panel-back noti-box">
                        <span class="icon-box bg-color-green set-icon">
                            <i class="fa fa-bars"></i>
                        </span>
                        <div class="text-box">
                            <p class="main-text">{{ $count }}
                                Клиенты</p>
                            <p class="text-muted">Clients</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="panel panel-back noti-box">
                        <span class="icon-box bg-color-blue set-icon">
                            <i class="fa fa-bell-o"></i>
                        </span>
                        <div class="text-box">
                            <p class="main-text">{{ $count }}
                                New</p>
                            <p class="text-muted">Notifications</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="panel panel-back noti-box">
                        <span class="icon-box bg-color-brown set-icon">
                            <i class="fa fa-rocket"></i>
                        </span>
                        <div class="text-box">
                            <p class="main-text">{{ count($admin) }}
                                Админ</p>
                            <p class="text-muted">Admin</p>
                        </div>
                    </div>
                </div>
            </div>
            <hr />
            <!-- /. ROW  -->
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Advanced Tables
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Имя</th>
                                            <th>Имя пользователя</th>
                                            <th>Чат ID</th>
                                            <th>Телефон</th>
                                            <th>Услуга</th>
                                            <th>Дата</th>
                                            <th>Статус</th>
                                            <th>Широта</th>
                                            <th>Долгота</th>
                                            <th>Создано</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($users as $user)
                                            <tr class="odd gradeX">
                                                <td>{{ $user->telegram_first_name }}</td>
                                                <td>{{ $user->telegram_username }}</td>
                                                <td>{{ $user->chat_id }}</td>
                                                <td>{{ $user->phone }}</td>
                                                <td>{{ $user->service }}</td>
                                                <td>{{ $user->date }}</td>
                                                <td><?= UserStatusEnum::getStatus($user->status) ?></td>
                                                <td>{{ $user->latitude }}</td>
                                                <td>{{ $user->longitude }}</td>
                                                <td>{{ $user->created_at }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Pagination Links -->
                            <div class="pagination">
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                    <!-- End Advanced Tables -->
                </div>
            </div>
        </div>
        <!-- /. PAGE INNER  -->
    </div>
</x-layouts.main>
