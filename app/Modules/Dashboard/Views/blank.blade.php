@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan')

@push('styles')
    <style>
        .error-page {
            min-height: calc(100vh - 180px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 15px;
        }

        .error-wrapper {
            width: 100%;
            max-width: 720px;
            text-align: center;
        }

        .error-illustration {
            position: relative;
            width: 100%;
            max-width: 390px;
            margin: 0 auto 25px;
        }

        .error-number {
            font-size: clamp(100px, 20vw, 190px);
            font-weight: 900;
            line-height: 1;
            letter-spacing: -12px;
            color: #e9edff;
            user-select: none;
        }

        .error-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 115px;
            height: 115px;
            border-radius: 32px;
            background: linear-gradient(135deg, #556ee6, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
            box-shadow: 0 15px 40px rgba(85, 110, 230, .3);
            transform: translate(-50%, -50%) rotate(-8deg);
        }

        .error-title {
            font-size: 30px;
            font-weight: 750;
            color: #343a40;
            margin-bottom: 12px;
        }

        .error-description {
            color: #74788d;
            font-size: 15px;
            line-height: 1.8;
            max-width: 490px;
            margin: 0 auto 28px;
        }

        .error-search {
            max-width: 460px;
            margin: 0 auto 25px;
        }

        .error-search .form-control {
            height: 48px;
            border-radius: 10px 0 0 10px;
            border-right: 0;
            padding-left: 16px;
        }

        .error-search .btn {
            border-radius: 0 10px 10px 0;
            padding: 0 20px;
        }

        .error-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .error-actions .btn {
            padding: 11px 22px;
            border-radius: 9px;
            font-weight: 600;
        }

        .error-footer {
            margin-top: 45px;
            color: #a6a9b7;
            font-size: 12px;
        }

        @media (max-width: 576px) {
            .error-title {
                font-size: 24px;
            }

            .error-number {
                letter-spacing: -7px;
            }

            .error-icon {
                width: 85px;
                height: 85px;
                font-size: 36px;
                border-radius: 24px;
            }

            .error-actions {
                flex-direction: column;
            }

            .error-actions .btn {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="error-page">
                <div class="error-wrapper">

                    {{-- Illustration --}}
                    <div class="error-illustration">
                        <div class="error-number">404</div>
                        <div class="error-icon">
                            <i class="mdi mdi-map-search-outline"></i>
                        </div>
                    </div>

                    {{-- Message --}}
                    <h2 class="error-title">
                        Silahkan Pilih Menu Yang Tersedia
                    </h2>

                    <p class="error-description">

                    </p>

                    {{-- Search --}}
                    <form action="{{ url('/search') }}" method="GET" class="error-search">

                        <div class="input-group">
                            <input type="text" name="q" class="form-control"
                                placeholder="Cari halaman yang Anda butuhkan..." aria-label="Cari halaman">

                            <button class="btn btn-primary" type="submit">
                                <i class="mdi mdi-magnify me-1"></i>
                                Cari
                            </button>
                        </div>
                    </form>

                    {{-- Actions --}}
                    <div class="error-actions">

                        <button type="button" class="btn btn-light border" onclick="history.back()">

                            <i class="mdi mdi-arrow-left me-1"></i>
                            Halaman Sebelumnya
                        </button>

                        <a href="{{ url('/dashboard/back-to-home') }}" class="btn btn-primary">

                            <i class="mdi mdi-home-outline me-1"></i>
                            Kembali ke Dashboard
                        </a>

                    </div>

                    <div class="error-footer">
                        <i class="mdi mdi-information-outline me-1"></i>
                        Jika masalah terus terjadi, silakan hubungi administrator.
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
