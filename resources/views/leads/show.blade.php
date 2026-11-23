<x-app-layout>
    <style>

        .ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default {
            border: transparent!important;
            background: transparent!important;
            font-weight: normal!important;
            color: #000!important;
        }

        .ui-widget-header{
            background: transparent!important;
            border: transparent!important;
        }

        .image-profile {
            background: #294d88;
            width: 80px;
            height: 80px;
            font-size: 45px;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            text-shadow: 0px 0px 5px #625d5d;
            border: 6px double #FFF;
            color: #ffffff;
        }

        .fileinput .thumbnail {
           max-width:300px!important;
           box-shadow: unset!important;
        }

        .sdm{
            width: 77px;
            justify-content: space-between;
            opacity: 0;
        }

        .contact-person .gh:hover .sdm {
            opacity: 1;
        }

        .wer {
            width: 250px;
        }

        .deletebox {
            width: 30px;
            height: 30px;
            background: #cff2fb;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50px;
        }

        .listdata h5 p {
            color: #262A2A;
            font-weight: 500;
        }

        .activity-icon {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: #fff;
            font-weight: bold;
        }

        a.email_s {
            border-radius: 50px;
            border-color: transparent!important;
            text-transform: capitalize;
            font-size: 16px;
            font-weight: 500;
        }

        a.email_s img {
            margin-right: 10px;
        }

        .imagedata {
            margin-left: 15px;
        }

        .imagebox img {
            border: 1px solid #3694cc;
            border-radius: 5px;
            overflow: hidden;
        }

        .imagedata p {
            font-size: 14px!important;
            font-weight: 500 !important;
            line-height: 17px;
            margin-bottom: 0px;
            font-family: 'Poppins', sans-serif;
            color: #414141;
        }

        button.btnsmall {
            border-radius: 50px !important;
            margin-left: 10px;
        }

        img.imagss {
            width: 65px;
            height: 65px;
            background: #eee;
            padding: 10px;
            border-radius: 50px;
            margin-right: 20px;
        }

        .activity-card {
            border-left: 4px solid #0d6efd;
            background-color: #f8f9fa;
        }

        .task-complete {
            text-decoration: line-through;
            color: #6c757d;
        }

        button.deletebtn {
            border: 0px;
            background: transparent;
            margin-left: 1px;
            cursor: pointer;
        }

        .per {
            justify-content: space-between;
            width: 75px;
            opacity: 0;
        }

        button.deletebtn:focus{
            outline: 0px;
        }

        button.deletebtn img {
            width: 22px;
            height: auto;
        }

        .card{
            color: #000 !important;
        }

        .new-task .card-header{
            border-bottom: 1px solid #E8E8E8!important;
            cursor: pointer;
        }

        .new-task .card-header a {
            color: #334C8B;
            font-size: 15px;
            font-weight: 500;
            line-height: 21px;
        }

        .new-task .card-header strong {
            font-size: 16px;
            color: #262A2A;
            font-weight: 600;
        }

        ul.list-task li p {
            color: #000;
            font-weight: 500;
            font-size: 14px;
            line-height: 21px;
            margin: 0px;
        }

        ul.list-task li {
            margin-bottom: 10px;
        }

        ul.list-task li p span.date {
            font-size: 12px;
            font-weight: 400;
            color: #636363;
            line-height: 21px;
        }

        .taskform .form-group label {
            display: none;
        }

        .taskform .form-control[readonly]{
            border: 1px solid #1072ae!important;
            border-radius: 10px!important; 
        }

        .taskform .form-control{
            border: 1px solid #1072ae!important;
            border-radius: 10px!important;
            height: 30px!important;
        }

        .new-task button.btn.btn-default {
            border-radius: 50px;
            font-size: 13px;
            font-weight: 500;
            height: 35px;
            line-height: 20px;
            width: 100%;
            text-align: center;
            padding: 1px 16px;
            min-width: 90px;
        }

        .new-task .card{
            padding: 0px!important;
        }

        .taskform .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #000 !important;
            text-transform: uppercase;
            font-size: 14px !important;
            height: 30px !important;
            line-height: 35px !important;
            font-weight: 500;
            text-transform: capitalize !important;
        }

        .taskform .form-control::placeholder {
            color: #000;
            font-weight: 500;
        }

        .contact-task-form .form-control::placeholder
        {
            color: #000;
            font-weight: 500;
        }


        .taskform .select2-container--default .select2-selection--single{
            border: 1px solid #1072ae!important;
            border-radius: 10px!important;
        }

        ul.list-task li h5 {
            font-size: 14px;
            font-weight: 500 !important;
            line-height: 17px;
            margin-bottom: 0px;
            font-family: 'Poppins', sans-serif;
            color: #000;
        }

        .contact-person p {
            font-size: 13px !important;
            font-weight: 400 !important;
            line-height: 21px !important;
            color: #414141;
            font-family: 'Poppins', sans-serif;
            letter-spacing: 0px;
            margin-bottom: 0px !important;
        }

        .editdelte {
            justify-content: space-around;
            width: 84px;
        }

        .contac-link {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            width: 23%;
        }

        .image-profile img {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }

        ul.cn-list li {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: flex-start;
            width: 100%;
        }

        .contact-task-form .form-control {
            border: 1px solid #1072ae !important;
            border-radius: 10px !important;
            height: 30px !important;
        }

        .contact-task-form label {
            display: none;
        }

        .infomation-data h3
        {
            margin: 0px;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0px;
            line-height: 19px;
            color: #3C4858!important;
        }

        .infomation-data p {
            font-size: 14px;
            font-weight: 400;
            color: #6F6F6F;
            margin: 2px 0px;
            line-height: 21px;
        }

        .inermain {
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            align-items: flex-start;
        }

        .infomation-data ul li {
            width: 65%;
            margin-bottom: 0px;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            margin-right: 20px;
        }



        .infomation-data ul{
            margin-bottom: 0px;
        }

        .infomation-data p {
            font-size: 14px;
            font-weight: 400;
            color: #6F6F6F;
            margin: 6px 0px;
            line-height: 21px;
            margin-bottom: 0px;
        }

        .imagedata p {
            margin-bottom: 0px;
        }

        .infomation-data ul li span {
            font-size: 14px;
            font-weight: 400;
            color: #3C4858;
            line-height: 21px;
        }

        .image-profile {
            margin-right: 10px;
        }

        .infomation-data ul {
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            align-items: center;
        }

        .button-box button img {
            margin-right: 5px;
        }
        .button-box button {
            border-radius: 50px;
            height: 48px;
            background: unset !important;
            background-color: #3779B7 !important;
            border-color: #3779B7 !important;
            font-size: 16px;
            font-weight: 500;
            color: #fff !important;
            text-transform: capitalize;
        }

        .button-box button:nth-child(1) {
            margin-right: 15px;
        }

        .infomation-data ul li img {
            margin-right: 8px;
        }

        .tabsection .nav-tabs .nav-item .nav-link {
            font-size: 14px;
            color: #565E5E!important;
            font-weight: 500;
            line-height: 21px;
            letter-spacing: 0px;
            text-transform: capitalize;
            border-bottom: 2px solid transparent!important;
        }

        .tabsection .nav-tabs .nav-item .nav-link.active {
            color: #3779B7 !important;
            font-weight: 600;
            border-bottom: 2px solid #3779B7 !important;
        }

        .tabsection .nav-tabs .nav-item {
            padding-right: 35px;
        }

        .listdata h5 {
            font-size: 14px;
            line-height: 16px;
            font-weight: 500;
            letter-spacing: 0px;
            color: #262A2A;
            font-family: 'Poppins', sans-serif;
            margin: 0px;
        }

        .listdata p {
            font-size: 14px;
            line-height: 21px;
            font-weight: 400;
            letter-spacing: 0px;
            font-family: 'Poppins', sans-serif;
            color: #6F6F6F;
            margin-bottom: 0px;
        }

        .tabsection h6.date_listing {
            font-size: 13px;
            font-weight: 400;
            line-height: 14px;
            letter-spacing: 0.5px;
            color: #334C8B !important;
            text-transform: capitalize;
            background: #D7F4FF;
            border-radius: 4px;
            width: 100%;
            max-width: 109px;
            padding: 5px 5px;
        }

        .list-one {
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            align-items: flex-start;
            width: 100%;
        }

        .buttonbox button:focus {
            outline: 0px;
        }

        .buttonbox button {
            border: 0px;
            background: transparent;
        }

        .list-one .image_box {
            margin-right: 10px;
        }

        .tabsection .greenbox {
            width: 40px;
            height: 40px;
            background: #CAF4C6;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 4px;
        }

        .tabsection .main-div {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding: 15px;

        }

        .list-two .dropdown-menu {
            top: 0 !important;
        }

        .list-two{
            position:relative;
        }

        .list-two button {
            background: transparent;
            border: 0px;
            width: 10px;
            height: auto;
        }

        .list-two button:focus {
            outline: 0px;
        }

        .list-two .dropdown-toggle::after{
            display: none;
        }

        .list-two .card-header p.hed {
            font-size: 18px!important;
            font-weight: 600!important;
            color: #262A2A!important;
            letter-spacing: 0px!important;
            line-height: 21px!important;
        }

        .tabsection .card-header{
            border-bottom: 1px solid #E8E8E8!important;
        }

        .donebtn{
            background: #2B6CF0 !important;
            border-radius: 32px!important;
            font-size: 14px;
            font-weight: 600;
        }

        .editbox img {
            /*width: 100%;*/
        }

        .editbox {
            width: 20px;
            height: auto;
        }

        .editbox {
            width: 30px;
            height: 30px;
            background: #cff2fb;
            display: flex;
            align-items: center;
            border-radius: 50px;
            justify-content: center;
        }

        ul.list-task li:hover .editbox {
            opacity: 1;
            cursor:pointer;
        }

        .contact-person {
            width: 140px;
        }

        .contact_edit {
            opacity: 0;
            width: 30px;
            height: 30px;
            background: #d1f5fe;
            border-radius: 50px;
            text-align: center;
            align-items: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        ul.cn-list li:hover .contact_edit {
            opacity: 1;
            cursor: pointer;
        }

        ul.list-task li:hover .per {
            opacity: 1;
        }


        .boxing {
            text-align: right;
            margin-top: 10px;
        }

        .border-s {
            border: 1px solid #1072ae !important;
            border-radius: 10px !important;
            padding: 7px 7px;
        }

        select#type {
            color: #000;
        }

        .appurt .contact-person {
            width: 100%;
        }

        ul.appurt li:hover .editsource {
            opacity: 1;
        }

        .editsource {
            width: 30px;
            height: 30px;
            background: #cff2fb;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50px;
        }


        @media only screen and (min-width: 768px) and (max-width: 991px){ 

            .tabsection .nav-tabs .nav-item {
                padding-right: 20px;
            }
        }

        @media (max-width: 767px){

            .tabsection .nav-tabs .nav-item {
                padding-right: 3px;
            }

            .inermain{
                flex-direction:column;
            }

            .mpadding {
                padding: 0px !important;
            }

            .mbcss {
                display: flex;
                flex-direction: column;
            }

            .infomation-data ul {
                flex-direction: column;
                width: 100%;
            }

            .infomation-data ul li {
                width: 100%;
                margin-bottom: 0px;
                display: flex;    
            }

            .mbcss .button-box {
                display: flex;
                flex-direction: row;
            }
        }

        /* Lead detail page: use the shared CRM colour tokens in both themes. */
        .lead-detail-page {
            color: var(--fk-list-text);
        }

        .lead-detail-page > .col-md-12 > .card {
            border: 1px solid var(--fk-list-border) !important;
            border-radius: 16px !important;
            background: var(--fk-list-bg) !important;
            box-shadow: none !important;
            overflow: hidden;
        }

        .lead-detail-page .card-header-warning {
            min-height: 58px;
            padding: 0 18px !important;
            border-bottom: 1px solid var(--fk-list-border) !important;
            background: var(--fk-list-panel) !important;
            box-shadow: none !important;
        }

        .lead-detail-page .card-header-warning .new_id {
            min-height: 58px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .lead-detail-page .card-header-warning .card-title {
            margin: 0 !important;
            color: var(--fk-list-heading) !important;
            font-size: 20px;
            font-weight: 700;
        }

        .lead-detail-page .card-header-warning a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border: 1px solid var(--fk-list-border-strong);
            border-radius: 10px;
            color: var(--fk-list-soft);
            background: var(--fk-list-control);
        }

        .lead-detail-page .card-body.shdnone {
            padding: 20px !important;
            background: transparent !important;
        }

        .lead-detail-page .mpadding {
            padding: 0 !important;
        }

        .lead-detail-page .mpadding > .card,
        .lead-detail-page .new-task,
        .lead-detail-page .new-task > .card,
        .lead-detail-page .tabsection > .card,
        .lead-detail-page .tab-pane .card {
            border: 1px solid var(--fk-list-border) !important;
            border-radius: 14px !important;
            background: var(--fk-list-panel) !important;
            color: var(--fk-list-text) !important;
            box-shadow: none !important;
        }

        .lead-detail-page .mpadding > .card {
            margin-bottom: 20px;
            padding: 22px !important;
        }

        .lead-detail-page .infomation-data h3,
        .lead-detail-page .new-task .card-header strong,
        .lead-detail-page .listdata h5,
        .lead-detail-page ul.list-task li h5,
        .lead-detail-page ul.list-task li p,
        .lead-detail-page .contact-person h5 {
            color: var(--fk-list-heading) !important;
        }

        .lead-detail-page .infomation-data p,
        .lead-detail-page .infomation-data ul li span,
        .lead-detail-page .contact-person p,
        .lead-detail-page .listdata p,
        .lead-detail-page .imagedata p,
        .lead-detail-page ul.list-task li p span.date {
            color: var(--fk-list-muted) !important;
        }

        .lead-detail-page .infomation-data > a,
        .lead-detail-page .infomation-data h3 a,
        .lead-detail-page .new-task .card-header a {
            color: var(--fk-list-accent) !important;
        }

        .lead-detail-page .infomation-data {
            min-width: 0;
        }

        .lead-detail-page .infomation-data ul {
            gap: 18px;
            padding: 0;
        }

        .lead-detail-page .infomation-data ul li {
            width: auto;
            margin-right: 0;
        }

        .lead-detail-page .image-profile {
            flex: 0 0 80px;
            border-color: var(--fk-list-accent);
            background: linear-gradient(135deg, var(--fk-list-accent-2), var(--fk-list-accent));
            text-shadow: none;
        }

        .lead-detail-page .infomation-data .bootstrap-select,
        .lead-detail-page .infomation-data .dropdown.bootstrap-select.form-control {
            width: 220px !important;
            margin-left: 12px;
        }

        .lead-detail-page .bootstrap-select > .dropdown-toggle,
        .lead-detail-page .form-control,
        .lead-detail-page .select2-container .select2-selection {
            border-color: var(--fk-form-border) !important;
            background: var(--fk-list-control) !important;
            color: var(--fk-list-text) !important;
        }

        .lead-detail-page .button-box {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: none;
        }

        .lead-detail-page .button-box .btn {
            min-width: 132px;
            height: 44px;
            margin: 0 !important;
            border: 1px solid var(--fk-list-border-strong) !important;
            border-radius: 12px !important;
            background: var(--fk-list-control) !important;
            color: var(--fk-list-text) !important;
            box-shadow: none !important;
        }

        .lead-detail-page .button-box .btn-primary {
            border-color: transparent !important;
            background: linear-gradient(135deg, var(--fk-list-accent), var(--fk-list-accent-2)) !important;
            color: var(--fk-list-primary-text) !important;
        }

        .lead-detail-page .new-task {
            margin-top: 0 !important;
            overflow: hidden;
        }

        .lead-detail-page .new-task > .card {
            margin: 0 !important;
            border-width: 0 0 1px !important;
            border-radius: 0 !important;
        }

        .lead-detail-page .new-task > .card:last-child {
            border-bottom: 0 !important;
        }

        .lead-detail-page .new-task .card-header,
        .lead-detail-page .tabsection .card-header {
            min-height: 54px;
            padding: 15px 18px !important;
            border-color: var(--fk-list-border) !important;
            background: var(--fk-list-head-bg) !important;
        }

        .lead-detail-page .new-task .card-body,
        .lead-detail-page .tabsection .card-body {
            background: transparent !important;
        }

        .lead-detail-page .tabsection > .card:first-child {
            margin-bottom: 14px;
            overflow: hidden;
        }

        .lead-detail-page .tabsection .nav-tabs {
            display: flex;
            flex-wrap: nowrap;
            padding: 0 8px;
            border: 0 !important;
            background: transparent !important;
        }

        .lead-detail-page .tabsection .nav-tabs .nav-item {
            flex: 1;
            padding: 0;
            text-align: center;
        }

        .lead-detail-page .tabsection .nav-tabs .nav-link {
            padding: 17px 10px !important;
            color: var(--fk-list-muted) !important;
        }

        .lead-detail-page .tabsection .nav-tabs .nav-link.active {
            border-bottom-color: var(--fk-list-accent) !important;
            background: var(--fk-list-count-bg) !important;
            color: var(--fk-list-accent) !important;
        }

        .lead-detail-page .tab-content {
            min-height: 360px;
            background: transparent !important;
        }

        .lead-detail-page .tab-pane .card.mb-2 {
            background: var(--fk-list-row) !important;
        }

        .lead-detail-page .tabsection h6.date_listing {
            color: var(--fk-list-accent) !important;
            background: var(--fk-list-count-bg) !important;
        }

        @media (max-width: 991px) {
            .lead-detail-page .mbcss {
                align-items: flex-start !important;
                gap: 18px;
            }

            .lead-detail-page .button-box {
                width: 100%;
            }
        }

        @media (max-width: 767px) {
            .lead-detail-page .card-body.shdnone {
                padding: 12px !important;
            }

            .lead-detail-page .inermain,
            .lead-detail-page .mbcss {
                width: 100%;
            }

            .lead-detail-page .infomation-data {
                width: 100%;
                margin-top: 14px;
            }

            .lead-detail-page .infomation-data > span {
                align-items: flex-start !important;
                flex-direction: column;
                gap: 10px;
            }

            .lead-detail-page .infomation-data .bootstrap-select,
            .lead-detail-page .infomation-data .dropdown.bootstrap-select.form-control {
                width: 100% !important;
                margin-left: 0;
            }

            .lead-detail-page .button-box .btn {
                flex: 1;
                min-width: 0;
            }

            .lead-detail-page .tabsection .nav-tabs {
                overflow-x: auto;
            }

            .lead-detail-page .tabsection .nav-tabs .nav-item {
                flex: 0 0 auto;
            }
        }


        /* Lead detail header + list polish */
        .lead-detail-page .card-body.shdnone > .container-fluid > .card:first-child,
        .lead-detail-page .lead-header-card {
            margin-top: 0 !important;
        }

        .lead-detail-page .lead-header-card .inermain {
            align-items: center;
            gap: 18px;
            min-width: 0;
        }

        .lead-detail-page .lead-header-card .image-profile {
            flex: 0 0 72px;
            width: 72px;
            height: 72px;
            margin: 0;
            border: 3px solid rgba(255, 255, 255, .25);
            font-size: 32px;
            font-weight: 700;
        }

        .lead-detail-page .lead-title-row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .lead-detail-page .lead-title-row h3 {
            font-size: 22px !important;
            line-height: 1.3 !important;
        }

        .lead-detail-page .lead-edit-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border: 1px solid var(--fk-list-border-strong);
            border-radius: 8px;
            background: var(--fk-list-control);
        }

        .lead-detail-page .lead-edit-link i {
            font-size: 16px;
        }

        .lead-detail-page .lead-title-row .bootstrap-select,
        .lead-detail-page .lead-title-row .dropdown.bootstrap-select.form-control {
            width: 170px !important;
            margin: 0 0 0 6px !important;
            padding: 0 !important;
            border: 0 !important;
            background: transparent !important;
        }

        .lead-detail-page .lead-title-row .bootstrap-select > .dropdown-toggle {
            width: 100% !important;
            height: 34px;
            padding: 4px 12px !important;
            border: 1px solid var(--fk-list-border-strong) !important;
            border-radius: 999px !important;
            font-size: 13px;
            text-transform: none;
        }

        .lead-detail-page .lead-url {
            display: inline-block;
            margin-top: 4px;
            font-size: 13px;
        }

        .lead-detail-page .lead-address {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 6px !important;
        }

        .lead-detail-page .lead-address i,
        .lead-detail-page .lead-meta i {
            font-size: 16px;
            color: var(--fk-list-accent);
        }

        .lead-detail-page .infomation-data ul.lead-meta {
            flex-wrap: wrap;
            gap: 8px;
            margin: 10px 0 0;
            list-style: none;
        }

        .lead-detail-page .lead-meta li,
        .lead-detail-page .lead-meta li a {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .lead-detail-page .lead-meta li {
            padding: 5px 12px;
            border: 1px solid var(--fk-list-border);
            border-radius: 999px;
            background: var(--fk-list-control);
        }

        .lead-detail-page .lead-meta li a span,
        .lead-detail-page .lead-meta li span {
            color: var(--fk-list-text) !important;
            font-size: 13px;
        }

        .lead-detail-page .button-box .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            text-transform: none;
        }

        .lead-detail-page .button-box .btn i {
            font-size: 18px;
        }

        .lead-detail-page ul.cn-list li {
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid var(--fk-list-border);
        }

        .lead-detail-page ul.cn-list li:last-child {
            border-bottom: 0;
        }

        .lead-detail-page .cn-list .contact-person h5 {
            margin: 0;
            font-size: 15px;
        }

        .lead-detail-page .cn-list .contact-person p:empty {
            display: none;
        }

        .lead-detail-page .contac-link {
            width: auto;
            gap: 8px;
            margin-left: auto;
            margin-right: 8px;
        }

        .lead-detail-page .contact-icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border: 1px solid var(--fk-list-border-strong);
            border-radius: 50%;
            background: var(--fk-list-control);
            color: var(--fk-list-accent) !important;
        }

        .lead-detail-page .contact-icon-btn i {
            font-size: 16px;
        }

        .lead-detail-page .lead-empty {
            margin: 4px 0;
            padding: 14px 0;
            text-align: center;
            font-size: 13px;
            color: var(--fk-list-muted) !important;
        }

        @media (max-width: 767px) {
            .lead-detail-page .lead-header-card .inermain {
                align-items: flex-start;
                flex-direction: column;
            }

            .lead-detail-page .lead-title-row .bootstrap-select,
            .lead-detail-page .lead-title-row .dropdown.bootstrap-select.form-control {
                width: 100% !important;
                margin-left: 0 !important;
            }
        }

        .shdnone {
            box-shadow: unset !important;
            background: #e3e9f6;
        }

        .forus {
            margin: 0px !important;
            box-shadow: unset !important;
            border-radius: 0px !important;
        }

        .new-task {
            background: #fff;
            box-shadow: 0 1px 4px 0 rgba(0, 0, 0, .14)!important;
            padding: 0!important;
            margin-top: 21px!important;
            border-radius: 6px!important;
            overflow: hidden;
        }

        button.btn.btn-primary.btn-sm.btn-icon-split.float-right.btnsmall {
            margin-bottom: 20px;
        }

        .forus .card-header {
            border-top: 1px solid #E8E8E8 !important;
        }
        .btn-group.kim button {
        border-radius: 50px;
        padding: 5px 10px;
        text-transform: capitalize;
        background: linear-gradient(45deg, #3860a4 0%, #3694cc 100%) !important;
        color: #fff !important;
        border-color: transparent;
    }

    button.btn.dropdown-toggle.btn-light {
        padding: 5px !important;
        width: 95%;
    }
    .dropdown.bootstrap-select.form-control {
        width: 30% !important;
    }

        .lead-detail-page .lead-meta li.lead-viewall-li {
            padding: 0;
            border-color: var(--fk-list-border-strong);
        }

        .lead-detail-page .lead-viewall-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 10px 5px 14px;
            border: 0;
            border-radius: 999px;
            background: transparent;
            cursor: pointer;
        }

        .lead-detail-page .lead-meta .lead-viewall-btn span {
            color: var(--fk-list-accent) !important;
        }

        .lead-detail-page .lead-viewall-btn i {
            font-size: 18px;
            transition: transform .2s;
        }

        .lead-detail-page .lead-viewall-btn.open i {
            transform: rotate(180deg);
        }

        .lead-detail-page .lead-all-info {
            display: none;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--fk-list-border);
        }

        .lead-detail-page .lead-all-info.open {
            display: block;
        }

        .lead-detail-page .lead-info-section {
            margin-bottom: 10px;
            border: 1px solid var(--fk-list-border);
            border-radius: 10px;
            background: var(--fk-list-control);
        }

        .lead-detail-page .lead-info-section-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 10px 14px;
            border: 0;
            background: transparent;
            color: var(--fk-list-heading);
            font-size: 14px;
            font-weight: 600;
            text-align: left;
            cursor: pointer;
        }

        .lead-detail-page .lead-info-section-head i {
            transition: transform .2s;
        }

        .lead-detail-page .lead-info-section.collapsed .lead-info-section-head i {
            transform: rotate(-90deg);
        }

        .lead-detail-page .lead-info-section.collapsed .lead-info-grid {
            display: none;
        }

        .lead-detail-page .lead-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 12px 20px;
            padding: 4px 14px 14px;
        }

        .lead-detail-page .lead-info-item label {
            display: block;
            margin: 0 0 2px;
            color: var(--fk-list-muted);
            font-size: 12px;
        }

        .lead-detail-page .lead-info-item div {
            color: var(--fk-list-text);
            font-size: 14px;
            word-break: break-word;
        }

        /* Contacts list */
        .lead-detail-page ul.cn-list li.lead-contact-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .lead-detail-page .lead-contact-avatar {
            flex: 0 0 38px;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 600;
            color: var(--fk-list-accent);
            background: var(--fk-list-control);
            border: 1px solid var(--fk-list-border-strong);
        }

        .lead-detail-page .lead-contact-item .contact-person {
            flex: 1 1 auto;
            width: auto;
            min-width: 0;
        }

        .lead-detail-page .lead-contact-item .contact-person h5 {
            line-height: 1.3;
            overflow-wrap: anywhere;
        }

        .lead-detail-page .lead-contact-item .contact-person p {
            line-height: 1.4 !important;
        }

        .lead-detail-page .lead-contact-phone {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-top: 2px;
            font-size: 12px;
            color: var(--fk-list-text);
        }

        .lead-detail-page .lead-contact-phone i {
            font-size: 13px;
            color: var(--fk-list-muted);
        }

        .lead-detail-page .lead-contact-item .contac-link {
            flex: 0 0 auto;
            margin: 0;
        }

        .lead-detail-page button.contact-icon-btn {
            padding: 0;
            cursor: pointer;
        }

        .lead-detail-page .contact-icon-btn:hover {
            border-color: var(--fk-list-accent);
        }

        .lead-detail-page .contact-call-btn.is-calling {
            opacity: .6;
            pointer-events: none;
        }

        /* Call history */
        .lead-detail-page .call-history-count {
            display: inline-block;
            min-width: 20px;
            margin-left: 6px;
            padding: 1px 6px;
            border-radius: 10px;
            font-size: 11px;
            line-height: 16px;
            text-align: center;
            color: var(--fk-list-accent);
            background: var(--fk-list-control);
        }

        .lead-detail-page .call-history-sub {
            font-size: 12px;
            color: var(--fk-list-muted);
        }

        .lead-detail-page .call-history-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .lead-detail-page .call-history-item {
            display: flex;
            gap: 12px;
            padding: 14px 0;
            border-bottom: 1px solid var(--fk-list-border);
        }

        .lead-detail-page .call-history-item:last-child {
            border-bottom: 0;
        }

        .lead-detail-page .call-history-icon {
            flex: 0 0 36px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--fk-list-control);
            border: 1px solid var(--fk-list-border-strong);
            color: var(--fk-list-accent);
        }

        .lead-detail-page .call-history-icon.in {
            color: #34d399;
        }

        .lead-detail-page .call-history-icon i {
            font-size: 18px;
        }

        .lead-detail-page .call-history-main {
            flex: 1 1 auto;
            min-width: 0;
        }

        .lead-detail-page .call-history-top {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 4px;
        }

        .lead-detail-page .call-history-top strong {
            font-size: 14px;
            color: var(--fk-list-heading);
        }

        .lead-detail-page .call-history-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 4px 14px;
            font-size: 12px;
            color: var(--fk-list-muted);
        }

        .lead-detail-page .call-history-meta span {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .lead-detail-page .call-history-meta i {
            font-size: 14px;
        }

        .lead-detail-page .call-history-remark {
            margin-top: 6px;
            font-size: 13px;
            color: var(--fk-list-text);
        }

        .lead-detail-page .call-history-audio {
            display: block;
            width: 100%;
            max-width: 340px;
            height: 32px;
            margin-top: 8px;
        }

        .lead-detail-page .call-history-time {
            flex: 0 0 auto;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            font-size: 12px;
            color: var(--fk-list-muted);
            white-space: nowrap;
        }

        .lead-detail-page .call-badge {
            display: inline-block;
            padding: 1px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 600;
            line-height: 18px;
        }

        .lead-detail-page .call-badge.ok { color: #10b981; background: rgba(16, 185, 129, .14); }
        .lead-detail-page .call-badge.warn { color: #f59e0b; background: rgba(245, 158, 11, .14); }
        .lead-detail-page .call-badge.bad { color: #ef4444; background: rgba(239, 68, 68, .14); }
        .lead-detail-page .call-badge.live { color: var(--fk-list-accent); background: var(--fk-list-control); }
        .lead-detail-page .call-badge.neutral { color: var(--fk-list-text); background: var(--fk-list-control); margin-right: 6px; }

        @media (max-width: 575px) {
            .lead-detail-page .call-history-item {
                flex-wrap: wrap;
            }

            .lead-detail-page .call-history-time {
                flex-direction: row;
                gap: 6px;
                width: 100%;
                padding-left: 48px;
            }
        }
        /* Left column: separate, evenly spaced cards instead of one joined panel */
        .lead-detail-page .new-task {
            display: flex;
            flex-direction: column;
            gap: 20px;
            overflow: visible;
            border: 0 !important;
            background: transparent !important;
        }

        .lead-detail-page .new-task > .card,
        .lead-detail-page .new-task > .card:last-child {
            margin: 0 !important;
            overflow: hidden;
            border: 1px solid var(--fk-list-border) !important;
            border-radius: 14px !important;
        }

        .lead-detail-page .new-task .forus .card-header {
            border-top: 0 !important;
            border-bottom: 1px solid var(--fk-list-border) !important;
        }

        .lead-detail-page .new-task .card-header strong {
            font-size: 16px;
        }

        .lead-detail-page .new-task .card-body {
            padding: 6px 18px !important;
        }

        .lead-detail-page .new-task .lead-empty {
            padding: 22px 0;
        }

        /* Contact row: avatar | details | actions */
        .lead-detail-page ul.cn-list li.lead-contact-item {
            align-items: flex-start;
            padding: 14px 0;
        }

        .lead-detail-page .lead-contact-item .contact-person h5 {
            margin-bottom: 2px;
            font-size: 15px;
            font-weight: 600;
        }

        .lead-detail-page .lead-contact-item .contact-person p {
            font-size: 12.5px !important;
        }

        .lead-detail-page .lead-contact-phone {
            white-space: nowrap;
            margin-top: 4px;
        }

        .lead-detail-page .lead-contact-item .contac-link {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 6px;
            max-width: 72px;
        }

        .lead-detail-page .lead-contact-item .contact-icon-btn {
            width: 30px;
            height: 30px;
        }

        .lead-detail-page .contact-icon-muted {
            color: var(--fk-list-muted) !important;
            opacity: .55;
            transition: opacity .15s ease, color .15s ease;
        }

        .lead-detail-page .lead-contact-item:hover .contact-icon-muted {
            opacity: 1;
        }

        .lead-detail-page .contact-icon-muted:hover {
            color: var(--fk-list-accent) !important;
        }

        .lead-detail-page .contact-icon-danger:hover {
            color: #ef4444 !important;
            border-color: #ef4444;
        }

        /* Tabs: keep the call count badge on the same line */
        .lead-detail-page .tabsection .nav-tabs .nav-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
        }
        /* Call remark */
        .lead-detail-page .call-remark-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-top: 8px;
            padding: 4px 12px;
            border: 1px solid var(--fk-list-accent);
            border-radius: 16px;
            background: transparent;
            color: var(--fk-list-accent);
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .lead-detail-page .call-remark-btn i {
            font-size: 15px;
        }

        .call-feedback-modal {
            border: 1px solid var(--fk-list-border-strong) !important;
            border-radius: 16px !important;
            background: var(--fk-list-panel, #0b1736) !important;
            color: var(--fk-list-text) !important;
            backdrop-filter: blur(12px);
        }

        .call-feedback-modal .modal-header,
        .call-feedback-modal .modal-footer {
            border-color: var(--fk-list-border) !important;
        }

        .call-feedback-modal .modal-title {
            font-weight: 600;
            color: var(--fk-list-heading);
        }

        .call-feedback-modal .modal-header small,
        .call-feedback-modal .call-feedback-hint {
            color: var(--fk-list-muted);
            font-size: 12px;
        }

        .call-feedback-modal .close {
            color: var(--fk-list-text);
            text-shadow: none;
        }

        .call-feedback-modal label {
            font-size: 12px;
            font-weight: 600;
            color: var(--fk-list-muted);
        }

        .call-feedback-modal .form-control {
            border: 1px solid var(--fk-list-border-strong) !important;
            border-radius: 10px !important;
            background: var(--fk-list-control) !important;
            color: var(--fk-list-text) !important;
            padding: 8px 12px;
        }

        .call-feedback-modal select.form-control {
            height: 42px;
        }

        .call-feedback-modal .call-feedback-error {
            margin: 0;
            min-height: 18px;
            font-size: 12px;
            color: #ef4444;
        }
        /* Email / LinkedIn drafts */
        .lead-detail-page .draft-new-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 12px;
            border: 1px solid var(--fk-list-accent);
            border-radius: 16px;
            background: transparent;
            color: var(--fk-list-accent);
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .lead-detail-page .draft-new-btn i { font-size: 16px; }

        .lead-detail-page .draft-form {
            margin: 6px 0 16px;
        }

        .lead-detail-page .draft-form .draft-text {
            min-height: 150px;
            border: 1px solid var(--fk-list-border-strong) !important;
            border-radius: 10px !important;
            background: var(--fk-list-control) !important;
            color: var(--fk-list-text) !important;
            padding: 10px 12px;
            white-space: pre-wrap;
        }

        .lead-detail-page .draft-form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-top: 10px;
        }

        .lead-detail-page .draft-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 0;
            border-bottom: 1px solid var(--fk-list-border);
        }

        .lead-detail-page .draft-item:last-child { border-bottom: 0; }

        .lead-detail-page .draft-icon {
            flex: 0 0 36px;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--fk-list-control);
            border: 1px solid var(--fk-list-border-strong);
            color: var(--fk-list-accent);
        }

        .lead-detail-page .draft-icon i { font-size: 18px; }
        .lead-detail-page .draft-main { flex: 1 1 auto; min-width: 0; }

        .lead-detail-page .draft-text-view,
        .lead-detail-page .activity-draft-text {
            white-space: pre-wrap;
            overflow-wrap: anywhere;
            font-size: 13px;
            line-height: 1.55;
            color: var(--fk-list-text);
        }

        .lead-detail-page .draft-meta {
            margin-top: 6px;
            font-size: 12px;
            color: var(--fk-list-muted);
        }

        .lead-detail-page .draft-actions {
            flex: 0 0 auto;
            display: flex;
            gap: 6px;
        }

        .lead-detail-page .draft-actions .contact-icon-btn { width: 30px; height: 30px; padding: 0; }
        .lead-detail-page .activity-draft-icon { font-size: 22px; color: #1b5e20; }
        .lead-detail-page .draft-status {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            margin-right: 6px;
            padding: 1px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            vertical-align: middle;
        }

        .lead-detail-page .draft-status i { font-size: 13px; }
        .lead-detail-page .draft-status.is-approved { color: #10b981; background: rgba(16, 185, 129, .13); }
        .lead-detail-page .draft-status.is-pending { color: #f59e0b; background: rgba(245, 158, 11, .14); }
        .lead-detail-page .draft-status.is-changes { color: #fb923c; background: rgba(251, 146, 60, .15); }
        .lead-detail-page .draft-for { display: inline-flex; align-items: center; gap: 3px; margin-right: 6px; padding: 2px 8px; border-radius: 10px; background: rgba(56, 189, 248, .12); color: #38bdf8; font-size: 11.5px; font-weight: 600; vertical-align: middle; }
        .lead-detail-page .draft-for i { font-size: 13px; }
        .lead-detail-page .draft-feedback { margin-top: 10px; padding: 10px 12px; border-left: 3px solid rgba(148, 163, 184, .5); border-radius: 8px; background: rgba(148, 163, 184, .08); }
        .lead-detail-page .draft-feedback.is-open { border-left-color: #fb923c; background: rgba(251, 146, 60, .1); }
        .lead-detail-page .draft-feedback-head { display: flex; align-items: center; gap: 5px; font-size: 12px; font-weight: 600; color: var(--fk-list-muted); }
        .lead-detail-page .draft-feedback.is-open .draft-feedback-head { color: #fb923c; }
        .lead-detail-page .draft-feedback-head i { font-size: 15px; }
        .lead-detail-page .draft-feedback p { margin: 5px 0 4px; font-size: 13.5px; color: var(--fk-list-text); white-space: pre-wrap; }
        .lead-detail-page .draft-feedback small { font-size: 11.5px; color: var(--fk-list-muted); }
        .lead-detail-page .draft-feedback-banner { margin-top: 10px; padding: 9px 12px; border-left: 3px solid #fb923c; border-radius: 8px; background: rgba(251, 146, 60, .1); font-size: 13px; color: var(--fk-list-text); white-space: pre-wrap; }
        .lead-detail-page .draft-feedback-banner b { color: #fb923c; }
        .lead-detail-page .draft-approver-row .draft-contact { width: 100%; }
        .lead-detail-page .draft-item.is-approved .draft-icon { border-color: #10b981; color: #10b981; }
        .lead-detail-page .lead-contact-link {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-top: 4px;
            font-size: 12px;
            font-weight: 600;
            color: var(--fk-list-accent) !important;
            white-space: nowrap;
        }

        .lead-detail-page .lead-contact-link i { font-size: 14px; }
        .lead-detail-page .lead-contact-link .lead-contact-link-out { font-size: 12px; opacity: .8; }
        .lead-detail-page .lead-contact-link:hover { text-decoration: underline; }
        .lead-detail-page .lead-contact-item .contact-person > .lead-contact-phone,
        .lead-detail-page .lead-contact-item .contact-person > .lead-contact-link { display: flex; width: fit-content; }
        /* Lead tasks */
        .lead-detail-page ul.lead-task-list li.task-item {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
            padding: 14px 0;
            border-bottom: 1px solid var(--fk-list-border);
        }

        .lead-detail-page ul.lead-task-list li.task-item:last-child { border-bottom: 0; }
        .lead-detail-page .task-item-main { flex: 1 1 auto; min-width: 0; }
        .lead-detail-page .task-item-top { display: flex; align-items: center; flex-wrap: wrap; gap: 8px; }
        .lead-detail-page .task-title { font-size: 14px; font-weight: 600; color: var(--fk-list-heading); overflow-wrap: anywhere; }

        .lead-detail-page .task-status {
            display: inline-block;
            padding: 1px 9px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            vertical-align: middle;
            white-space: nowrap;
        }

        .lead-detail-page .task-status.is-pending { color: #f59e0b; background: rgba(245, 158, 11, .14); }
        .lead-detail-page .task-status.is-open { color: #38bdf8; background: rgba(56, 189, 248, .13); }
        .lead-detail-page .task-status.is-progress { color: #a78bfa; background: rgba(167, 139, 250, .14); }
        .lead-detail-page .task-status.is-done { color: #10b981; background: rgba(16, 185, 129, .13); }

        .lead-detail-page .task-meta,
        .lead-detail-page .task-timeline {
            display: flex;
            flex-wrap: wrap;
            gap: 4px 14px;
            margin-top: 6px;
            font-size: 12px;
            color: var(--fk-list-muted);
        }

        .lead-detail-page .task-meta span,
        .lead-detail-page .task-timeline span { display: inline-flex; align-items: center; gap: 4px; }
        .lead-detail-page .task-meta i,
        .lead-detail-page .task-timeline i { font-size: 14px; }
        .lead-detail-page .task-timeline span { color: var(--fk-list-dim); }
        .lead-detail-page .task-timeline span.is-done { color: #10b981; }

        .lead-detail-page .task-remark {
            display: flex;
            gap: 6px;
            margin-top: 6px;
            padding: 6px 10px;
            border-radius: 8px;
            background: var(--fk-list-control);
            font-size: 12px;
            color: var(--fk-list-text);
            overflow-wrap: anywhere;
        }

        .lead-detail-page .task-remark i { font-size: 14px; color: var(--fk-list-muted); }
        .lead-detail-page .task-actions { flex: 0 0 auto; display: flex; gap: 6px; }
        .lead-detail-page .task-actions .contact-icon-btn { width: 30px; height: 30px; padding: 0; }
        .lead-detail-page .draft-approver-row { margin-top: 10px; }
        .lead-detail-page .draft-approver-row label { display: block; margin-bottom: 4px; font-size: 12px; font-weight: 600; color: var(--fk-list-muted); }
        .lead-detail-page .draft-approver-row .draft-approver {
            height: 40px;
            border: 1px solid var(--fk-list-border-strong) !important;
            border-radius: 10px !important;
            background: var(--fk-list-control) !important;
            color: var(--fk-list-text) !important;
            font-size: 13px;
        }
        /* Send approved email */
        .lead-detail-page .draft-send-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            height: 30px;
            padding: 0 12px;
            border: 0;
            border-radius: 15px;
            background: #10b981;
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
        }

        .lead-detail-page .draft-send-btn i { font-size: 15px; }

        /* Send email popup (resets the layout's global label styles inside it) */
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-card,
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-card * { letter-spacing: normal !important; text-transform: none !important; }
        :is(#sendDraftModal, #sendLinkedinModal) .modal-dialog { max-width: 560px; margin: 1.5rem auto !important; min-height: calc(100% - 3rem) !important; display: flex; align-items: center; transform: none !important; }
        :is(#sendDraftModal, #sendLinkedinModal) .modal-dialog > .sdm-card { width: 100%; }
        /* Note: not ".sdm" - that class is a hover-only dropdown elsewhere on this page (opacity: 0). */
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-card { opacity: 1 !important; overflow: hidden; display: flex; flex-direction: column; max-height: calc(100vh - 3rem); border-radius: 18px !important; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-head { display: flex; align-items: center; gap: 12px; padding: 18px 20px; border-bottom: 1px solid var(--fk-list-border); }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-head-icon { flex: 0 0 40px; width: 40px; height: 40px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; background: rgba(16, 185, 129, .14); color: #10b981; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-head-copy { flex: 1 1 auto; min-width: 0; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-head-copy h5 { margin: 0; font-size: 17px; font-weight: 700; color: var(--fk-list-heading); text-align: left; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-head-copy p { margin: 2px 0 0; font-size: 12.5px; color: var(--fk-list-muted); }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-close { flex: 0 0 34px; width: 34px; height: 34px; border: 0; border-radius: 10px; background: var(--fk-list-control); color: var(--fk-list-muted); cursor: pointer; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-close:hover { color: var(--fk-list-heading); }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-body { flex: 1 1 auto; min-height: 0; overflow-y: auto; padding: 16px 20px 4px; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-section { margin-bottom: 18px; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-section-head { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-step { width: 20px; height: 20px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; background: var(--fk-list-count-bg); border: 1px solid var(--fk-list-count-border); color: var(--fk-list-accent); font-size: 11px; font-weight: 700; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-section-title { font-size: 13px; font-weight: 700; color: var(--fk-list-heading); }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-count { font-size: 12px; color: var(--fk-list-muted); }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-link { margin-left: auto; padding: 0; border: 0; background: none; color: var(--fk-list-accent); font-size: 12px; font-weight: 600; cursor: pointer; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-recipients { display: flex; flex-direction: column; gap: 6px; max-height: 210px; overflow-y: auto; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-recipient { position: relative; display: flex; align-items: center; gap: 10px; margin: 0; padding: 9px 12px; border: 1px solid var(--fk-list-border); border-radius: 12px; background: var(--fk-list-control); cursor: pointer; transition: border-color .15s ease; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-recipient input { position: absolute; opacity: 0; pointer-events: none; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-check { flex: 0 0 20px; width: 20px; height: 20px; border-radius: 6px; border: 1.5px solid var(--fk-list-border-strong); display: inline-flex; align-items: center; justify-content: center; color: transparent; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-check i { font-size: 15px; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-recipient input:checked ~ .sdm-check { background: var(--fk-list-accent); border-color: var(--fk-list-accent); color: var(--fk-list-primary-text); }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-recipient.is-checked { border-color: var(--fk-list-accent); }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-avatar { flex: 0 0 32px; width: 32px; height: 32px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; background: var(--fk-list-panel); border: 1px solid var(--fk-list-border-strong); color: var(--fk-list-accent); font-size: 11px; font-weight: 700; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-recipient-copy { display: flex; flex-direction: column; min-width: 0; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-name { font-size: 13.5px; font-weight: 600; color: var(--fk-list-heading); }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-email { font-size: 12px; color: var(--fk-list-accent); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-title { font-size: 11.5px; color: var(--fk-list-muted); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-subject { padding: 10px 12px; border: 1px solid var(--fk-list-border); border-radius: 12px; background: var(--fk-list-control); font-size: 13px; color: var(--fk-list-text); }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-subject.is-empty { color: var(--fk-list-muted); font-style: italic; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-apps { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 8px; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-app { margin: 0; cursor: pointer; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-app input { position: absolute; opacity: 0; pointer-events: none; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-app-card { display: flex; flex-direction: column; align-items: flex-start; gap: 2px; height: 100%; padding: 10px 12px; border: 1px solid var(--fk-list-border); border-radius: 12px; background: var(--fk-list-control); transition: border-color .15s ease; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-app-card i { margin-bottom: 4px; font-size: 20px; color: var(--fk-list-muted); }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-app-name { font-size: 13px; font-weight: 600; color: var(--fk-list-heading); }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-app-hint { font-size: 11px; line-height: 1.3; color: var(--fk-list-muted); }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-app:hover .sdm-app-card { border-color: var(--fk-list-border-strong); }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-app input:checked + .sdm-app-card { border-color: var(--fk-list-accent); box-shadow: 0 0 0 1px var(--fk-list-accent) inset; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-app input:checked + .sdm-app-card i { color: var(--fk-list-accent); }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-empty { padding: 26px 10px; text-align: center; color: var(--fk-list-muted); }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-empty i { font-size: 32px; color: var(--fk-list-dim); }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-error { margin: 0 0 8px; min-height: 18px; font-size: 12px; color: #ef4444; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-foot { display: flex; justify-content: flex-end; gap: 10px; padding: 14px 20px; border-top: 1px solid var(--fk-list-border); }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-btn { display: inline-flex; align-items: center; gap: 6px; height: 40px; padding: 0 18px; border: 1px solid var(--fk-list-border-strong); border-radius: 10px; background: transparent; color: var(--fk-list-text); font-size: 13.5px; font-weight: 600; cursor: pointer; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-btn i { font-size: 17px; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-btn.is-primary { border-color: transparent; background: linear-gradient(135deg, #10b981, #0891b2); color: #fff; }
        @media (max-width: 575px) {
            :is(#sendDraftModal, #sendLinkedinModal) .sdm-apps { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            :is(#sendDraftModal, #sendLinkedinModal) .sdm-head, :is(#sendDraftModal, #sendLinkedinModal) .sdm-body, :is(#sendDraftModal, #sendLinkedinModal) .sdm-foot { padding-left: 16px; padding-right: 16px; }
        }
        /* LinkedIn send popup (reuses the Send email popup styles) */
        #sendLinkedinModal .sdm-card,
        #sendLinkedinModal .sdm-card * { letter-spacing: normal !important; text-transform: none !important; }
        .lead-detail-page .draft-linkedin-btn { background: #0a66c2; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-head-icon-li { background: rgba(10, 102, 194, .18); color: #4ba3f5; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-radio { border-radius: 50%; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-message { max-height: 160px; overflow-y: auto; white-space: pre-wrap; overflow-wrap: anywhere; line-height: 1.5; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-note { display: flex; gap: 8px; margin-bottom: 10px; padding: 10px 12px; border-radius: 12px; background: rgba(10, 102, 194, .12); font-size: 12px; line-height: 1.45; color: var(--fk-list-text); }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-note i { font-size: 16px; color: #4ba3f5; }
        :is(#sendDraftModal, #sendLinkedinModal) .sdm-btn.is-linkedin { background: linear-gradient(135deg, #0a66c2, #0891b2); }
        /* Lead files */
        .lead-detail-page .lead-files-form { margin-bottom: 16px; }
        .lead-detail-page .lead-dropzone { position: relative; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 4px; margin: 0; padding: 22px 16px; border: 1.5px dashed var(--fk-list-border-strong); border-radius: 14px; background: var(--fk-list-control); text-align: center; cursor: pointer; transition: border-color .15s ease, background .15s ease; }
        .lead-detail-page .lead-dropzone:hover, .lead-detail-page .lead-dropzone.is-over { border-color: var(--fk-list-accent); background: var(--fk-list-count-bg); }
        .lead-detail-page .lead-dropzone input { position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
        .lead-detail-page .lead-dropzone i { font-size: 30px; color: var(--fk-list-accent); }
        .lead-detail-page .lead-dropzone strong { font-size: 14px; color: var(--fk-list-heading); letter-spacing: normal !important; text-transform: none !important; }
        .lead-detail-page .lead-dropzone strong span { color: var(--fk-list-accent); text-decoration: underline; }
        .lead-detail-page .lead-dropzone small { font-size: 11.5px; color: var(--fk-list-muted); letter-spacing: normal !important; text-transform: none !important; }
        .lead-detail-page .lead-file-queue { list-style: none; margin: 10px 0 0; padding: 0; }
        .lead-detail-page .lead-file-queue li { display: flex; justify-content: space-between; gap: 10px; padding: 6px 10px; border-radius: 8px; background: var(--fk-list-control); font-size: 12.5px; color: var(--fk-list-text); margin-bottom: 4px; }
        .lead-detail-page .lead-file-queue li.is-bad { color: #ef4444; }
        .lead-detail-page .lead-file-queue li span:last-child { color: var(--fk-list-muted); white-space: nowrap; }
        .lead-detail-page .lead-file-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 10px; }
        .lead-detail-page .lead-file-error { margin: 8px 0 0; font-size: 12px; color: #ef4444; }
        .lead-detail-page .lead-file-list { list-style: none; margin: 0; padding: 0; }
        .lead-detail-page .lead-file-item { display: flex; align-items: center; gap: 12px; padding: 12px 0; border-bottom: 1px solid var(--fk-list-border); }
        .lead-detail-page .lead-file-item:last-child { border-bottom: 0; }
        .lead-detail-page .lead-file-thumb { flex: 0 0 44px; width: 44px; height: 44px; border-radius: 10px; overflow: hidden; border: 1px solid var(--fk-list-border-strong); }
        .lead-detail-page .lead-file-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .lead-detail-page .lead-file-icon { flex: 0 0 44px; width: 44px; height: 44px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; background: var(--fk-list-control); border: 1px solid var(--fk-list-border-strong); color: var(--fk-list-accent); }
        .lead-detail-page .lead-file-icon i { font-size: 22px; }
        .lead-detail-page .lead-file-icon.is-pdf { color: #ef4444; }
        .lead-detail-page .lead-file-icon.is-sheet { color: #10b981; }
        .lead-detail-page .lead-file-icon.is-doc { color: #3b82f6; }
        .lead-detail-page .lead-file-icon.is-slides { color: #f59e0b; }
        .lead-detail-page .lead-file-icon.is-zip { color: #a78bfa; }
        .lead-detail-page .lead-file-main { flex: 1 1 auto; min-width: 0; }
        .lead-detail-page .lead-file-name { display: block; font-size: 13.5px; font-weight: 600; color: var(--fk-list-heading) !important; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .lead-detail-page .lead-file-name:hover { color: var(--fk-list-accent) !important; }
        .lead-detail-page .lead-file-meta { display: block; margin-top: 2px; font-size: 12px; color: var(--fk-list-muted); }
        .lead-detail-page .lead-file-buttons { flex: 0 0 auto; display: flex; gap: 6px; }
        .lead-detail-page .lead-file-buttons .contact-icon-btn { width: 32px; height: 32px; }
        /* File preview popup */
        .lead-detail-page button.lead-file-thumb, .lead-detail-page button.lead-file-icon { padding: 0; cursor: pointer; }
        .lead-detail-page button.lead-file-name { padding: 0; border: 0; background: none; text-align: left; max-width: 100%; cursor: pointer; }
        .lead-detail-page .lead-file-buttons button.contact-icon-btn { padding: 0; cursor: pointer; }
        #filePreviewModal .fpv-card, #filePreviewModal .fpv-card * { letter-spacing: normal !important; text-transform: none !important; }
        #filePreviewModal .modal-dialog { max-width: min(1100px, 94vw); margin: 1.5rem auto !important; min-height: calc(100% - 3rem) !important; display: flex; align-items: center; transform: none !important; }
        #filePreviewModal .fpv-card { width: 100%; opacity: 1 !important; overflow: hidden; display: flex; flex-direction: column; max-height: calc(100vh - 3rem); border-radius: 16px !important; }
        #filePreviewModal .fpv-head { display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-bottom: 1px solid var(--fk-list-border); }
        #filePreviewModal .fpv-head-icon { color: var(--fk-list-accent); }
        #filePreviewModal .fpv-title { flex: 1 1 auto; min-width: 0; margin: 0; font-size: 15px; font-weight: 600; color: var(--fk-list-heading); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; text-align: left; }
        #filePreviewModal .fpv-btn { display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border: 0; border-radius: 9px; background: var(--fk-list-control); color: var(--fk-list-text) !important; cursor: pointer; }
        #filePreviewModal .fpv-btn:hover { color: var(--fk-list-accent) !important; }
        #filePreviewModal .fpv-body { flex: 1 1 auto; min-height: 0; display: flex; align-items: center; justify-content: center; padding: 12px; overflow: auto; background: rgba(0, 0, 0, .25); }
        #filePreviewModal .fpv-body img { max-width: 100%; max-height: calc(100vh - 140px); object-fit: contain; border-radius: 8px; }
        #filePreviewModal .fpv-body iframe { width: 100%; height: calc(100vh - 140px); border: 0; border-radius: 8px; background: #fff; }
        #filePreviewModal .fpv-body video { max-width: 100%; max-height: calc(100vh - 140px); border-radius: 8px; }
        #filePreviewModal .fpv-body audio { width: min(520px, 100%); }
        #filePreviewModal .fpv-none { padding: 40px 20px; text-align: center; color: var(--fk-list-muted); }
        #filePreviewModal .fpv-none i { display: block; margin-bottom: 8px; font-size: 40px; color: var(--fk-list-dim); }
        #filePreviewModal .fpv-none a { display: inline-flex; align-items: center; gap: 6px; margin-top: 12px; padding: 8px 16px; border-radius: 10px; background: var(--fk-list-accent); color: var(--fk-list-primary-text) !important; font-weight: 600; }
        /* Call remark popup (own class names; resets the layout's label styles) */
        #callFeedbackModal .cfm-card, #callFeedbackModal .cfm-card * { letter-spacing: normal !important; text-transform: none !important; }
        #callFeedbackModal .modal-dialog { max-width: 520px; margin: 1.5rem auto !important; min-height: calc(100% - 3rem) !important; display: flex; align-items: center; transform: none !important; }
        #callFeedbackModal .cfm-card { width: 100%; opacity: 1 !important; overflow: hidden; max-height: calc(100vh - 3rem); border-radius: 18px !important; }
        #callFeedbackModal .cfm-form { display: flex; flex-direction: column; max-height: calc(100vh - 3rem); margin: 0; }
        #callFeedbackModal .cfm-head { display: flex; align-items: center; gap: 12px; padding: 16px 20px; border-bottom: 1px solid var(--fk-list-border); }
        #callFeedbackModal .cfm-head-icon { flex: 0 0 40px; width: 40px; height: 40px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; background: rgba(34, 211, 238, .14); color: var(--fk-list-accent); }
        #callFeedbackModal .cfm-head-copy { flex: 1 1 auto; min-width: 0; }
        #callFeedbackModal .cfm-head-copy h5 { margin: 0; font-size: 17px; font-weight: 700; color: var(--fk-list-heading); text-align: left; }
        #callFeedbackModal .cfm-head-copy p { margin: 2px 0 0; font-size: 12.5px; color: var(--fk-list-muted); }
        #callFeedbackModal .cfm-close { flex: 0 0 34px; width: 34px; height: 34px; border: 0; border-radius: 10px; background: var(--fk-list-control); color: var(--fk-list-muted); cursor: pointer; }
        #callFeedbackModal .cfm-body { flex: 1 1 auto; min-height: 0; overflow-y: auto; padding: 16px 20px 4px; }
        #callFeedbackModal .cfm-section { margin-bottom: 16px; }
        #callFeedbackModal .cfm-label { margin-bottom: 8px; font-size: 13px; font-weight: 700; color: var(--fk-list-heading); }
        #callFeedbackModal .cfm-req { color: #ef4444; }
        #callFeedbackModal .cfm-hint { margin: 6px 0 0; font-size: 12px; color: var(--fk-list-muted); }
        #callFeedbackModal .cfm-outcomes { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px; }
        #callFeedbackModal .cfm-outcome { position: relative; margin: 0; cursor: pointer; }
        #callFeedbackModal .cfm-outcome:last-child:nth-child(odd) { grid-column: 1 / -1; }
        #callFeedbackModal .cfm-outcome input { position: absolute; opacity: 0; pointer-events: none; }
        #callFeedbackModal .cfm-outcome span { display: flex; align-items: center; gap: 8px; padding: 11px 12px; border: 1px solid var(--fk-list-border); border-radius: 12px; background: var(--fk-list-control); font-size: 13.5px; font-weight: 600; color: var(--fk-list-heading); transition: border-color .15s ease, background .15s ease; }
        #callFeedbackModal .cfm-outcome span i { font-size: 19px; }
        #callFeedbackModal .cfm-outcome.is-green span i { color: #10b981; }
        #callFeedbackModal .cfm-outcome.is-amber span i { color: #f59e0b; }
        #callFeedbackModal .cfm-outcome.is-grey span i { color: #94a3b8; }
        #callFeedbackModal .cfm-outcome.is-red span i { color: #ef4444; }
        #callFeedbackModal .cfm-outcome.is-blue span i { color: #38bdf8; }
        #callFeedbackModal .cfm-outcome:hover span { border-color: var(--fk-list-border-strong); }
        #callFeedbackModal .cfm-outcome input:checked + span { border-color: var(--fk-list-accent); background: var(--fk-list-count-bg); box-shadow: 0 0 0 1px var(--fk-list-accent) inset; }
        #callFeedbackModal .cfm-input { display: block; width: 100%; height: 42px; padding: 0 12px; border: 1px solid var(--fk-list-border-strong) !important; border-radius: 12px !important; background: var(--fk-list-control) !important; color: var(--fk-list-text) !important; font-size: 13.5px; color-scheme: dark; }
        #callFeedbackModal .cfm-input:focus { outline: none; border-color: var(--fk-list-accent) !important; }
        #callFeedbackModal .cfm-textarea { height: auto; min-height: 84px; padding: 10px 12px; resize: vertical; }
        #callFeedbackModal .cfm-select-wrap { position: relative; }
        #callFeedbackModal .cfm-select-wrap select { appearance: none; -webkit-appearance: none; padding-right: 36px; cursor: pointer; }
        #callFeedbackModal .cfm-select-wrap i { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: var(--fk-list-muted); pointer-events: none; }
        #callFeedbackModal .cfm-select-wrap option { background: #0b1736; color: #e8f0ff; }
        #callFeedbackModal .cfm-callback { padding: 12px; border-radius: 12px; background: rgba(56, 189, 248, .08); border: 1px solid rgba(56, 189, 248, .3); }
        #callFeedbackModal .cfm-error { margin: 0 0 8px; min-height: 18px; font-size: 12px; color: #ef4444; }
        #callFeedbackModal .cfm-foot { display: flex; justify-content: flex-end; gap: 10px; padding: 14px 20px; border-top: 1px solid var(--fk-list-border); }
        #callFeedbackModal .cfm-btn { display: inline-flex; align-items: center; gap: 6px; height: 40px; padding: 0 18px; border: 1px solid var(--fk-list-border-strong); border-radius: 10px; background: transparent; color: var(--fk-list-text); font-size: 13.5px; font-weight: 600; cursor: pointer; }
        #callFeedbackModal .cfm-btn i { font-size: 17px; }
        #callFeedbackModal .cfm-btn.is-primary { border-color: transparent; background: linear-gradient(135deg, var(--fk-list-accent), var(--fk-list-accent-2)); color: var(--fk-list-primary-text); }
        #callFeedbackModal .cfm-btn:disabled { opacity: .6; cursor: wait; }
        #callFeedbackModal .cfm-channels { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px; }
        #callFeedbackModal .cfm-channel { position: relative; margin: 0; cursor: pointer; }
        #callFeedbackModal .cfm-channel input { position: absolute; opacity: 0; pointer-events: none; }
        #callFeedbackModal .cfm-channel span { display: flex; align-items: center; gap: 8px; padding: 10px 12px; border: 1px solid var(--fk-list-border); border-radius: 12px; background: var(--fk-list-control); font-size: 13px; font-weight: 600; color: var(--fk-list-heading); }
        #callFeedbackModal .cfm-channel span i { font-size: 18px; }
        #callFeedbackModal .cfm-channel.is-whatsapp span i { color: #25d366; }
        #callFeedbackModal .cfm-channel.is-linkedin span i { color: #4ba3f5; }
        #callFeedbackModal .cfm-channel.is-email span i { color: #f59e0b; }
        #callFeedbackModal .cfm-channel.is-none span i { color: #94a3b8; }
        #callFeedbackModal .cfm-channel input:checked + span { border-color: var(--fk-list-accent); background: var(--fk-list-count-bg); box-shadow: 0 0 0 1px var(--fk-list-accent) inset; }
        #callFeedbackModal .cfm-details { padding: 12px; border-radius: 12px; background: rgba(16, 185, 129, .07); border: 1px solid rgba(16, 185, 129, .28); }
        /* Calling progress */
        .lead-detail-page .contact-call-badge { display: inline-flex; align-items: center; gap: 4px; width: fit-content; margin-top: 5px; padding: 2px 9px; border-radius: 12px; font-size: 11.5px; font-weight: 600; }
        .lead-detail-page .contact-call-badge i { font-size: 13px; }
        .lead-detail-page .contact-call-badge.is-green { color: #10b981; background: rgba(16, 185, 129, .13); }
        .lead-detail-page .contact-call-badge.is-amber { color: #f59e0b; background: rgba(245, 158, 11, .14); }
        .lead-detail-page .contact-call-badge.is-grey { color: #94a3b8; background: rgba(148, 163, 184, .14); }
        .lead-detail-page .contact-call-badge.is-red { color: #ef4444; background: rgba(239, 68, 68, .13); }
        .lead-detail-page .contact-call-badge.is-blue { color: #38bdf8; background: rgba(56, 189, 248, .13); }
        .lead-detail-page .contact-call-badge.is-none { color: var(--fk-list-muted); background: var(--fk-list-control); border: 1px dashed var(--fk-list-border-strong); }
        .lead-detail-page .lead-contact-item .contact-person > .contact-call-badge { display: flex; }
        .lead-detail-page .lead-progress-pill { display: inline-flex !important; align-items: center; gap: 6px; padding: 6px 12px !important; border-radius: 20px; font-size: 13px; font-weight: 600; }
        .lead-detail-page .lead-progress-pill i { font-size: 16px; }
        .lead-detail-page .lead-progress-pill.is-none { color: #94a3b8 !important; background: rgba(148, 163, 184, .12); }
        .lead-detail-page .lead-progress-pill.is-none span { color: #94a3b8 !important; }
        .lead-detail-page .lead-progress-pill.is-partial { color: #f59e0b !important; background: rgba(245, 158, 11, .12); }
        .lead-detail-page .lead-progress-pill.is-partial span { color: #f59e0b !important; }
        .lead-detail-page .lead-progress-pill.is-all { color: #10b981 !important; background: rgba(16, 185, 129, .12); }
        .lead-detail-page .lead-progress-pill.is-all span { color: #10b981 !important; }
    </style>
    <div class="row lead-detail-page">
        <div class="col-md-12">
            <div class="card shdnone mt-0 p-0">
                <div class="card-header m-0 card-header-tabs card-header-warning">
                    <div class="nav-tabs-navigation">
                        <div class="nav-tabs-wrapper new_id">
                            <h4 class="card-title ">
                            Company Overview </h4>
                            <a href="{{route('leads.index')}}"><i class="material-icons">arrow_circle_left</i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body shdnone">
                    @if(count($errors) > 0)
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <i class="material-icons">close</i>
                        </button>
                        <span>
                            @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                            @endforeach
                        </span>
                    </div>
                    @endif
                    @if(session('message_success'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <i class="material-icons">close</i>
                        </button>
                        <span>
                            {{ session('message_success') }}
                        </span>
                    </div>
                    @endif
                    @if(session('message_info'))
                    <div class="alert alert-info">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <i class="material-icons">close</i>
                        </button>
                        <span>
                            {{ session('message_info') }}
                        </span>
                    </div>
                    @endif


<!--  -->
<div class="container-fluid p-4 mpadding">

<!--  -->
<!-- <button type="button" data-toggle="modal" data-target="#updateLeadModel" class="btn btn-primary btn-sm btn-icon-split float-right btnsmall">
    <span class="text">Update Leads</span>
    <button type="button" data-toggle="modal" data-target="#AddAddressModel" onclick="openAddAddressModel()" class="btn btn-primary btn-sm btn-icon-split float-right btnsmall">
        <span class="text">Add Address</span>
    </button>
</button> -->
<!--  -->
<!-- Header -->
<div class="card p-0 px-3 py-3 lead-header-card">
    <div class="d-flex justify-content-between align-items-center mb-0 mbcss">
        <div class="inermain">
            <div class="image-profile">
                {{ strtoupper(substr($lead->company_name, 0, 1)) }}
            </div>
            <div class="infomation-data">
                <div class="lead-title-row">
                    <h3>{{ $lead->company_name ?? '' }}</h3>
                    <a href="{{ route('leads.edit', [$lead->id]) }}" class="lead-edit-link" title="Edit Lead"><i class="material-icons">edit</i></a>
                    <select name="status" id="status" class="form-control selectpicker">
                        <option value="0" {{$lead->status == 0 ? 'selected' : ''}}>Pending</option>
                        @if($status->count() > 0)
                        @foreach($status as $stat)
                        <option value="{{$stat['id']}}" {{$lead->status == $stat['id'] ? 'selected' : ''}}> {{$stat['display_name']}} </option>
                        @endforeach
                        @endif
                    </select>
                </div>
                @if($lead->company_url)
                <a href="{{ $lead->company_url }}" target="_blank" class="lead-url">{{ $lead->company_url }}</a>
                @endif
                @if($address_data)
                <p class="lead-address"><i class="material-icons">place</i> {{ $address_data }}</p>
                @endif
                <ul class="lead-meta">
                    @if($lead_contacts[0]->phone_number ?? '')
                    <li>
                        <a href="tel:{{ $lead_contacts[0]->phone_number }}"><i class="material-icons">call</i> <span>{{ $lead_contacts[0]->phone_number }}</span></a>
                    </li>
                    @endif
                    @if($lead_contacts[0]->email ?? '')
                    <li>
                        <a href="mailto:{{ $lead_contacts[0]->email }}"><i class="material-icons">mail</i> <span>{{ $lead_contacts[0]->email }}</span></a>
                    </li>
                    @endif
                    @if($lead->lead_source)
                    <li><i class="material-icons">campaign</i> <span>{{ $lead->lead_source }}</span></li>
                    @endif
                    @php
                        $dialableCount = $lead_contacts->filter(fn ($contact) => trim((string) $contact->phone_number) !== '')->count();
                        $calledCount = $lead_contacts->filter(fn ($contact) => isset($contact_call_summary[$contact->id]))->count();
                    @endphp
                    @if($dialableCount > 0)
                    <li class="lead-progress-pill {{ $calledCount === 0 ? 'is-none' : ($calledCount >= $dialableCount ? 'is-all' : 'is-partial') }}" title="Contacts called">
                        <i class="material-icons">{{ $calledCount >= $dialableCount ? 'task_alt' : 'phone_forwarded' }}</i>
                        <span>{{ $calledCount >= $dialableCount ? 'All '.$dialableCount.' contacts called' : 'Called '.$calledCount.' / '.$dialableCount.' contacts' }}</span>
                    </li>
                    @endif
                    <li class="lead-viewall-li">
                        <button type="button" class="lead-viewall-btn" id="leadViewAllBtn" onclick="toggleLeadAllInfo()"><span>View All</span> <i class="material-icons">expand_more</i></button>
                    </li>
                </ul>
            </div>
        </div>
        <div class="button-box">
            <button class="btn btn-primary me-2" onclick="activateNotesTab()"><i class="material-icons">sticky_note_2</i> Note</button>
        </div>
    </div>

    @php
        $firstContact = $lead_contacts[0] ?? null;
        $statusName = $lead->status == 0 ? 'Pending' : ($status->firstWhere('id', $lead->status)->display_name ?? '');
        $extraOthers = $lead->others ? (json_decode($lead->others, true) ?: []) : [];
        $infoSections = [
            'Lead Details' => [
                'Lead Type' => $statusName,
                'Firm Name' => $lead->company_name,
                'Lead Source' => $lead->lead_source,
                'Assigned To' => $lead->assign_user->name ?? '',
                'Website' => $lead->company_url,
                'Lead Generation Date' => $lead->lead_generation_date ? \Carbon\Carbon::parse($lead->lead_generation_date)->format('M j, Y') : '',
                'Revenue (Rs Cr)' => $lead->revenue_rs_cr,
            ],
            'Contact Details' => [
                'Customer Name' => $firstContact->name ?? '',
                'Designation' => $firstContact->title ?? '',
                'Mobile Number' => $firstContact->phone_number ?? '',
                'Alternate Number' => $lead->alternate_number,
                'Email Id' => $firstContact->email ?? '',
            ],
            'Address Details' => [
                'Address' => $address->address1 ?? '',
                'Place' => $address->address2 ?? '',
                'State' => $address->statename->state_name ?? '',
                'District' => $address->districtname->district_name ?? '',
                'City' => $address->cityname->city_name ?? '',
                'Pincode' => $address->pincodename->pincode ?? '',
            ],
            'Other Details' => collect($extraOthers)
                ->mapWithKeys(fn ($value, $key) => [($key === 'others' ? 'Other' : ucwords(str_replace('_', ' ', $key))) => is_scalar($value) ? $value : json_encode($value)])
                ->merge([
                    'Others 1' => $lead->others_1,
                    'Others 2' => $lead->others_2,
                    'Others 3' => $lead->others_3,
                    'Others 4' => $lead->others_4,
                    'Others 5' => $lead->others_5,
                ])->all(),
            'Note' => [
                'Note' => trim(html_entity_decode(strip_tags($lead->notes->first()?->note ?? ''))),
            ],
        ];
    @endphp
    <div class="lead-all-info" id="leadAllInfo">
        @foreach($infoSections as $sectionTitle => $fields)
        <div class="lead-info-section">
            <button type="button" class="lead-info-section-head" onclick="this.parentElement.classList.toggle('collapsed')">
                {{ $sectionTitle }} <i class="material-icons">expand_more</i>
            </button>
            <div class="lead-info-grid">
                @foreach($fields as $fieldLabel => $fieldValue)
                <div class="lead-info-item">
                    <label>{{ $fieldLabel }}</label>
                    <div>{{ ($fieldValue !== null && $fieldValue !== '' && strtoupper((string) $fieldValue) !== 'N/A') ? $fieldValue : '-' }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    function toggleLeadAllInfo() {
        document.getElementById('leadAllInfo').classList.toggle('open');
        document.getElementById('leadViewAllBtn').classList.toggle('open');
    }
</script>

<!-- Main Content -->
<div class="row">
<!-- Left Column: Tasks and Contacts -->
<div class="col-md-4 ">
    <div class="new-task">
<!-- Tasks -->
<div class="card forus mb-4">
    <div class="card-header d-flex justify-content-between">
        <strong onclick="toggleCardBody()">Tasks</strong>
        <a class="text-decoration-none" onclick="createTask()">+ Add</a>
    </div>
    <div class="card-body" id="cardBody">

<!--  -->
<form method="POST" 
action="{{ route('lead-tasks.store') }}" class="form-horizontal taskform" id="frmLeadTaskCreate" enctype="multipart/form-data" style="display:none;">
@csrf
<div class="row">
    <div class="col-md-12 pr-1 pl-1">
        <div class="col-md-12 form-group">
            <label for="users">User<span style="color:red">*</span></label>
            <select class="select2" name="assigned_to" id="assigned_to" data-style="select-with-transition" title="Select User">
                <option value="">Select User</option>
                @foreach($task_users as $taskUser)
                <option value="{{ $taskUser->id }}">{{ $taskUser->name }}{{ (int) $taskUser->id === (int) auth()->id() ? ' (me)' : '' }}</option>
                @endforeach
            </select>
            @if($errors->has('assigned_to'))
            <p class="help-block">
                <strong>{{ $errors->first('assigned_to') }}</strong>
            </p>
            @endif
        </div>
    </div>

    <div class="col-md-12 pr-1 pl-1">
        <div class="col-md-12 form-group">
            <label for="description">Description<span style="color:red">*</span></label>
            <input type="hidden" name="lead_id" value="{{$lead->id??''}}">
            <input type="hidden" name="task_id"  id="task_id" value="">
            <input type="text"  name="description" id="description" value="{{ old('description','') }}"  class="form-control" placeholder="Description">
            @if($errors->has('description'))
            <p class="help-block">
                <strong>{{ $errors->first('description') }}</strong>
            </p>
            @endif
        </div>
    </div>
    <div class="col-md-12 pr-1 pl-1">
        <div class="col-md-12 form-group">
            <label for="priority">Priority<span style="color:red">*</span></label>
            <select name="priority" id="priority" class="select2" required>
            <option value="">Select Priority</option>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
            </select>
            @if($errors->has('priority'))
            <p class="help-block">
                <strong>{{ $errors->first('priority') }}</strong>
            </p>
            @endif
        </div>
    </div>
    <div class="col-md-12 pr-1 pl-1">
        <div class="col-md-12 form-group">
            <label for="status">Status<span style="color:red">*</span></label>
            <select name="status" id="status" class="select2" required>
                <option value="pending" selected>Pending</option>
                <option value="open">Open</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
            </select>
            @if($errors->has('status'))
            <p class="help-block">
                <strong>{{ $errors->first('status') }}</strong>
            </p>
            @endif
        </div>
    </div>
            
    <div class="col-md-6 pr-1 pl-1">
        <div class="col-md-12 form-group">
            <label for="date">Date <span style="color:red">*</span></label>

            <input type="text"  name="date" id="task_date" value="{{ old('date','') }}"  class="form-control datepicker" readonly="true" placeholder="Date">
            @if($errors->has('date'))
            <p class="help-block">
                <strong>{{ $errors->first('date') }}</strong>
            </p>
            @endif
        </div>
    </div>
    <div class="col-md-6 pr-1 pl-1">
        <div class="col-md-12 form-group">
            <label for="time">Time <span style="color:red">*</span></label>
            <input type="time"  name="time" id="time" value="{{ old('time','') }}"  class="form-control" placeholder="Time">
            @if($errors->has('time'))
            <p class="help-block">
                <strong>{{ $errors->first('time') }}</strong>
            </p>
            @endif
        </div>     
    </div>

    <div class="col-md-6 pr-1 pl-1">
        <div class="col-md-12 form-group">
            <div class="d-flex flex-row">
                <button type="button" class="btn btn-default mr-2" onclick="$('#frmLeadTaskCreate').hide()">Cancel</button>
                <button type="submit" class="btn btn-default">Save</button>
            </div>
        </div>
    </div>

</div>
</form>

@if($lead_tasks->isEmpty())
<p class="lead-empty">No tasks yet.</p>
@endif
<ul class="list-group list-group-flush list-task contact-list lead-task-list">
    @foreach($lead_tasks as $lead_task)
    @include('leads.partials.task-item')
    @endforeach
</ul>
</div>
<!--  -->
</div>

<!-- Contacts -->
<div class="card forus">
    <div class="card-header d-flex justify-content-between" >
        <strong onclick="toggleCardBody1()">Contacts</strong>
        <a  class="text-decoration-none" onclick="createContact()">+ Add</a>
    </div>
    <div class="card-body" id="cardBody1">

<!--  -->
<form method="POST" 
action="{{ route('lead-contacts.store') }}" class="form-horizontal contact-task-form" id="frmLeadContactsCreate" enctype="multipart/form-data" style="display:none;">
@csrf
<div class="row">
    <div class="col-md-12 pr-1 pl-1">
        <div class="col-md-12 form-group">
            <label for="name">Contact Name <span style="color:red">*</span></label>
            <input type="hidden" name="contact_id" id="contact_id" value="">
            <input type="hidden" name="lead_id" value="{{$lead->id??''}}">
            <input type="text"  name="name" id="name" value="{{ old('name','') }}"  class="form-control" placeholder="Contact Name">
            @if($errors->has('name'))
            <p class="help-block">
                <strong>{{ $errors->first('name') }}</strong>
            </p>
            @endif
        </div>
    </div>
    <div class="col-md-12 pr-1 pl-1">
        <div class="col-md-12 form-group">
            <label for="title">Title <span style="color:red">*</span></label>

            <input type="text"  name="title" id="title" value="{{ old('title','') }}"  class="form-control" placeholder="Title">
            @if($errors->has('title'))
            <p class="help-block">
                <strong>{{ $errors->first('title') }}</strong>
            </p>
            @endif
        </div>
    </div>
    <div class="col-md-12 pr-1 pl-1">
        <div class="col-md-12 form-group">
            <label for="phone_number">Phone Number <span style="color:red">*</span></label>
            <input type="text"  name="phone_number" id="phone_number" value="{{ old('phone_number','') }}"  class="form-control" placeholder="Phone Number">
            @if($errors->has('phone_number'))
            <p class="help-block">
                <strong>{{ $errors->first('phone_number') }}</strong>
            </p>
            @endif
        </div>     
    </div>
    <div class="col-md-6 pr-1 pl-1">
        <div class="col-md-12 form-group">
            <label for="contact_email">Email <span style="color:red">*</span></label>
            <input type="text"  name="contact_email" id="contact_email" value="{{ old('contact_email','') }}"  class="form-control" placeholder="Email">
            @if($errors->has('contact_email'))
            <p class="help-block">
                <strong>{{ $errors->first('contact_email') }}</strong>
            </p>
            @endif
        </div>     
    </div>
    <div class="col-md-6 pr-1 pl-1">
        <div class="col-md-12 form-group">
            <label for="url">url <span style="color:red">*</span></label>
            <input type="text"  name="url" id="url" value="{{ old('Url','') }}"  class="form-control" placeholder="Url">
            @if($errors->has('url'))
            <p class="help-block">
                <strong>{{ $errors->first('url') }}</strong>
            </p>
            @endif
        </div>     
    </div>
    <div class="col-md-6 pr-1 pl-1">
        <div class="col-md-12 form-group">
            <div class="d-flex flex-row mt-2">
                <button type="submit" class="btn btn-default mr-2">Save</button>
                <button type="button" class="btn btn-default" onclick="$('#frmLeadContactsCreate').hide()">Cancel</button>

            </div>
        </div>
    </div>

</div>
</form>
<!--  -->
<ul class="list-group list-group-flush list-task cn-list">
    @foreach($lead_contacts as $lead_contact)
    <li class="lead-contact-item">
        <div class="lead-contact-avatar">{{ strtoupper(collect(preg_split('/\s+/', trim($lead_contact->name ?? '')))->filter()->take(2)->map(fn ($part) => mb_substr($part, 0, 1))->implode('')) ?: '?' }}</div>
        <div class="contact-person">
            <h5>{{$lead_contact->name??''}}</h5>
            <p>{{$lead_contact->title??''}}</p>
            @if($lead_contact->phone_number)
            <span class="lead-contact-phone"><i class="material-icons">phone</i>{{ $lead_contact->phone_number }}</span>
            @endif
            @php
                // Only real web links (adds https:// when the sheet left it out).
                $contactUrl = trim((string) $lead_contact->url);
                if ($contactUrl !== '' && strtoupper($contactUrl) !== 'N/A' && ! preg_match('#^https?://#i', $contactUrl)) {
                    $contactUrl = 'https://' . ltrim($contactUrl, '/');
                }
                $contactUrl = filter_var($contactUrl, FILTER_VALIDATE_URL) ? $contactUrl : null;
            @endphp
            @php
                $lastCall = $contact_call_summary[$lead_contact->id] ?? null;
            @endphp
            @if($lastCall)
            <span class="contact-call-badge {{ $lastCall['tone'] }}" title="Last call {{ $lastCall['at']?->format('d M Y, h:i A') }}"><i class="material-icons">call</i>{{ $lastCall['label'] }} · {{ $lastCall['at']?->diffForHumans() }}</span>
            @elseif($lead_contact->phone_number)
            <span class="contact-call-badge is-none"><i class="material-icons">phone_callback</i>Not called yet</span>
            @endif
            @if($contactUrl)
            <a href="{{ $contactUrl }}" target="_blank" rel="noopener noreferrer" class="lead-contact-link" title="{{ $contactUrl }}">
                <i class="material-icons">{{ str_contains(strtolower($contactUrl), 'linkedin.com') ? 'work' : 'link' }}</i>{{ str_contains(strtolower($contactUrl), 'linkedin.com') ? 'LinkedIn profile' : 'Profile link' }}<i class="material-icons lead-contact-link-out">open_in_new</i>
            </a>
            @endif
        </div>
        <div class="contac-link">
            @if($lead_contact->email)
            <a href="mailto:{{ $lead_contact->email }}" class="contact-icon-btn" title="{{ $lead_contact->email }}" data-toggle="tooltip"><i class="material-icons">mail</i></a>
            @endif
            @if($lead_contact->phone_number)
            <button type="button" class="contact-icon-btn contact-call-btn"
                data-call-url="{{ route('leads.contacts.call', [$lead->id, $lead_contact->id]) }}"
                data-contact-name="{{ $lead_contact->name }}"
                data-contact-phone="{{ $lead_contact->phone_number }}"
                title="Call {{ $lead_contact->phone_number }}" data-toggle="tooltip"><i class="material-icons">call</i></button>
            @endif
            <button type="button" class="contact-icon-btn contact-icon-muted" onclick="editContact('{{$lead_contact}}')" title="Edit contact" data-toggle="tooltip"><i class="material-icons">edit</i></button>
            <form action="{{ route('lead-contacts.destroy', $lead_contact->id) }}" method="POST" class="d-inline-flex m-0" onsubmit="return confirm('Do you really want to delete?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="contact-icon-btn contact-icon-muted contact-icon-danger" title="Delete contact" data-toggle="tooltip"><i class="material-icons">delete</i></button>
            </form>
        </div>
</li>
@endforeach
</ul>
</div>
</div>

<!--opportunities  -->
<div class="card forus">
    <div class="card-header d-flex justify-content-between" >
        <strong onclick="toggleCardBody2()">Opportunities</strong>
        <a onclick="createOpportunity()" class="text-decoration-none">+ Add</a>
    </div>
    <div class="card-body" id="cardBody2">

<!--  -->
<form method="POST" 
action="{{ route('lead-opportunities.store') }}" class="form-horizontal taskform" id="frmLeadOpportunitiesCreate" enctype="multipart/form-data" style="display:none;" >
@csrf
<div class="row">
<div class="col-md-12 pr-1 pl-1">
        <div class="col-md-12 form-group">
            <label for="amount">Amount</label>
            <input type="number" step="0.01" min="0"  name="amount" id="o_amount" value="{{ old('amount','0.00') }}"  class="form-control" placeholder="Amount">
            @if($errors->has('amount'))
            <p class="help-block">
                <strong>{{ $errors->first('amount') }}</strong>
            </p>
            @endif
        </div>
    </div>
    <div class="col-md-12 pr-1 pl-1">
        <div class="col-md-12 form-group">
            <label for="users">User<span style="color:red">*</span></label>
            <select class="select2" name="assigned_to" id="o_assigned_to" data-style="select-with-transition" title="Select User">
                <option value="">Select User</option>
                @if(@isset($users))
                @foreach($users as $user)
                <option value="{!! $user->id !!}">{!! $user->name !!}</option>
                @endforeach
                @endif
            </select>
            @if($errors->has('assigned_to'))
            <p class="help-block">
                <strong>{{ $errors->first('assigned_to') }}</strong>
            </p>
            @endif
        </div>
    </div>

    <div class="col-md-12 pr-1 pl-1">
        <div class="col-md-12 form-group">
            <label for="lead_contact_id">Contact<span style="color:red">*</span></label>
            <select class="select2" name="lead_contact_id" id="lead_contact_id" data-style="select-with-transition" title="Select Contact">
                <option value="">Select Contact</option>
                @if(@isset($lead_contacts))
                @foreach($lead_contacts as $lead_contact)
                <option value="{!! $lead_contact->id !!}">{!! $lead_contact->name !!}</option>
                @endforeach
                @endif
            </select>
            @if($errors->has('lead_contact_id'))
            <p class="help-block">
                <strong>{{ $errors->first('lead_contact_id') }}</strong>
            </p>
            @endif
        </div>
    </div>

    <div class="col-md-12 pr-1 pl-1">
        <div class="col-md-12 form-group">
            <label for="note">Note<span style="color:red">*</span></label>
            <input type="hidden" name="lead_id" value="{{$lead->id??''}}">
            <input type="hidden" name="opportunity_id"  id="opportunity_id" value="">
            <input type="text"  name="note" id="o_note" value="{{ old('note','') }}"  class="form-control" placeholder="Note">
            @if($errors->has('note'))
            <p class="help-block">
                <strong>{{ $errors->first('note') }}</strong>
            </p>
            @endif
        </div>
    </div>
    <div class="col-md-12 pr-1 pl-1">
        <div class="col-md-12 form-group">
            <label for="confidence" class="d-block mb-0">Confidence<span style="color:red">*</span></label>

            <div class="boxing mt-0">
                <span id="confidenceValue">50%</span>
            </div>
            <div class="border-s">
                <input type="range" class="form-control-range" name="confidence" id="confidence" value="50" min="0" max="100">
            </div>

<!-- <input type="text"  name="confidence" id="confidence" value="{{ old('confidence','') }}"  class="form-control" placeholder="Confidence"> -->


@if($errors->has('confidence'))
<p class="help-block">
    <strong>{{ $errors->first('confidence') }}</strong>
</p>
@endif
</div>
</div>
<div class="col-md-12 pr-1 pl-1">
    <div class="col-md-12 form-group">
        <label for="estimated_close_date">Estimated Close Date <span style="color:red">*</span></label>

        <input type="text"  name="estimated_close_date" id="estimated_close_date" value="{{ old('estimated_close_date','') }}"  class="form-control datepicker" readonly="true" placeholder="Date">
        @if($errors->has('estimated_close_date'))
        <p class="help-block">
            <strong>{{ $errors->first('estimated_close_date') }}</strong>
        </p>
        @endif
    </div>
</div>
<div class="col-md-12 pr-1 pl-1">
    <div class="col-md-12 ">
        {!! Form::select('status', $opportunity_status,old('status',''), array('class' => 'form-control','id'=>'status')) !!}

        @if($errors->has('status'))
        <p class="help-block">
            <strong>{{ $errors->first('status') }}</strong>
        </p>
        @endif
    </div>
</div>
<div class="col-md-6 pr-1 pl-1">
    <div class="col-md-12 form-group">
        <div class="d-flex flex-row mt-3">
            <button type="submit" class="btn btn-default mr-2">Save</button>
            <button type="button" class="btn btn-default" onclick="$('#frmLeadOpportunitiesCreate').hide()">Cancel</button>
        </div>

    </div>
</div>

</div>
</form>
<!--  -->
@if($lead_opportunities->isEmpty())
<p class="lead-empty">No opportunities yet.</p>
@endif
<ul class="list-group list-group-flush list-task cn-list appurt">
    @foreach($lead_opportunities  as $lead_opportunity)
    <li>
        <div class="contact-person">
            <div class="d-flex flex-row justify-content-between gh">
                <div class="">
                    <h5>{{$lead_opportunity->note}}</h5>
                    <p>{{$lead_opportunity->amount}}</p>
                </div>
                <div class="d-flex flex-row sdm">
                    <div class="editsource" onclick="editOpportunity('{{$lead_opportunity}}')"><img src="{{ url('/').'/'.asset ('assets/img/ph_note-pencil-fill.svg') }}">
                    </div>
                    <div class="deletebox">
                        <form action="{{ route('lead-opportunities.destroy', $lead_opportunity->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Do you really want to delete?')">
                            @csrf
                            @method('DELETE')
                            <button class="deletebtn editsource" type="submit" class="">
                                <img src="{{ url('/').'/'.asset ('assets/img/deletbox.svg') }}">
                            </button>
                        </form>
                    </div>

                </div>
            </div>


        </div>
    </li>
    @endforeach
</ul>
</div>
</div>

<!-- opportunities end -->
</div>

</div>

<!-- Right Column: Activities -->
<div class="col-md-8">

    <div class="tabsection">

<!--- new tab--->
<div class="card p-0">
<!-- Tab Navigation -->
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="activities-tab" data-toggle="tab" href="#activities" role="tab">Activities</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="call-history-tab" data-toggle="tab" href="#call-history" role="tab">Call History @if($lead_call_history->count())<span class="call-history-count">{{ $lead_call_history->count() }}</span>@endif</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="notes-tab" data-toggle="tab" href="#notes" role="tab">Notes</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="files-tab" data-toggle="tab" href="#files" role="tab">Files @if(count($media_items))<span class="call-history-count">{{ count($media_items) }}</span>@endif</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="email-drafts-tab" data-toggle="tab" href="#email-drafts" role="tab">Email Draft @if($email_drafts->count())<span class="call-history-count">{{ $email_drafts->count() }}</span>@endif</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="linkedin-drafts-tab" data-toggle="tab" href="#linkedin-drafts" role="tab">LinkedIn Draft @if($linkedin_drafts->count())<span class="call-history-count">{{ $linkedin_drafts->count() }}</span>@endif</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="tasks-tab" data-toggle="tab" href="#tasks" role="tab">Tasks</a>
    </li>

</ul>
</div>

<div class="card p-0">
<!-- Tab Content -->
<div class="tab-content p-0 mt-0" id="myTabContent">
    <div class="tab-pane fade show active" id="activities" role="tabpanel">

        <div class="card-header">
            <p class="hed m-0">Activities</p>
        </div>
        <div class="card-body">

            {{--<div class="mb-3">
                <form method="POST" 
                action="{{ route('lead-notes.store') }}" class="form-horizontal" id="frmLeadNotesAdd" enctype="multipart/form-data" style="display:none;">
                @method('POST')
                @csrf
                <input type="hidden" name="lead_id" value="{{$lead->id??''}}">
                <input type="hidden" name="note_id" id="note_id" >
                {!! Form::textarea('note', old('note',''), ['class' => 'form-control rounded border ckeditor-init', 'placeholder' => 'Add a note...','rows'=>8,'id'=>'note_text']) !!}
                <div class="text-end mt-2">
                    <button class="btn btn-sm btn-primary donebtn" type="submit">Done</button>
                </div>
            </form>
        </div>--}}
        @php
        $previousDate = null;
        @endphp
        @if($combined->isEmpty())
        <p class="lead-empty">No activity yet.</p>
        @endif
        @foreach($combined as $lead_note)
        @php
        $noteDate = \Carbon\Carbon::parse($lead_note->created_at)->format('d M Y'); // e.g., 28 Feb 2024
        $noteTime = \Carbon\Carbon::parse($lead_note->created_at)->format('h:i a'); // e.g., 10:25 pm
        @endphp
        <div>
            @if($noteDate !== $previousDate)
            <h6 class="text-muted date_listing mt-3"> <img src="{{ url('/').'/'.asset('assets/img/leaddate.svg') }}"> {{ $noteDate }}</h6>
            @php
            $previousDate = $noteDate;
            @endphp
            @endif
<!-- code 28 feb 2024  -->

<!--new div--->
<div class="card mb-2 p-0">
    <div class="main-div">
        <div class="list-one">
            <div class="image_box">
                <div class="greenbox">
                    @if($lead_note->type == 'email')
                    <i class="material-icons activity-draft-icon">mail</i>
                    @elseif($lead_note->type == 'linkedin')
                    <i class="material-icons activity-draft-icon">work</i>
                    @elseif($lead_note->type == 'note')
                    <img src="{{url('/').'/'.asset('assets/img')}}/cup.svg">
                    @elseif($lead_note->type == 'task')
                    <img src="{{url('/').'/'.asset('assets/img')}}/task_logo.png" width="35">
                    @elseif($lead_note->type == 'log')
                    <img src="{{url('/').'/'.asset('assets/img')}}/log.png" width="35">
                    @elseif($lead_note->type == 'call_log')
                    <img src="{{url('/').'/'.asset('assets/img')}}/call_log.png" width="35">
                    @else
                    <img src="{{url('/').'/'.asset('assets/img')}}/opportunity.png?" width="25">
                    @endif
                </div>
            </div>
            <div class="listdata">
                @if(in_array($lead_note->type, ['email', 'linkedin'], true))
                <h5>{{ $lead_note->type == 'email' ? 'Email Draft' : 'LinkedIn Draft' }}
                    @if($lead_note->isApproved())
                    <span class="draft-status is-approved"><i class="material-icons">verified</i> Approved</span>
                    @else
                    <span class="draft-status is-pending"><i class="material-icons">schedule</i> Pending approval</span>
                    @endif
                </h5>
                <div class="activity-draft-text">{{ \Illuminate\Support\Str::limit($lead_note->note, 400) }}</div>
                <p>Created by: {{ $lead_note->createdby->name ?? '' }}</p>
                @elseif($lead_note->type == 'note')
                <h5>Note</h5>
                <h5>{!! $lead_note->note??''!!}</h5>
                <p>Created by: {!! $lead_note->createdby? $lead_note->createdby->name : ''!!}</p>
                @elseif($lead_note->type == 'task')
                <h5>Task <span class="badge badge-info ml-1" style="font-family: 'Poppins', sans-serif;font-size: 10px;font-weight:700;border-radius: 0px 10px !important;"> {{$lead_note->priority??''}}</span>
                    <span class="task-status {{ ['pending' => 'is-pending', 'open' => 'is-open', 'in_progress' => 'is-progress', 'completed' => 'is-done'][$lead_note->status] ?? 'is-pending' }}">{{ $lead_note->statusLabel() }}</span></h5>
                <h5>{!! $lead_note->description??''!!} ({{date('d M Y', strtotime($lead_note->date))}}, {{date('h:i A', strtotime($lead_note->time))}})</h5>
                <p>Created by: {!! $lead_note->createdby? $lead_note->createdby->name : ''!!}</p>
                @elseif($lead_note->type == 'log')
                <h5>Log </h5>
                <h5>{!! $lead_note->message??''!!}</h5>
                @elseif($lead_note->type == 'call_log')
                <h5>Call Log </h5>
                <h5>{!! $lead_note->remark??''!!}</h5>
                <p>Created by: {!! $lead_note->user? $lead_note->user->name : ''!!}</p>
                @else
                <h5>Opportunity <span class="badge badge-info ml-1" style="font-family: 'Poppins', sans-serif;font-size: 10px;font-weight:700;border-radius: 0px 10px !important;"> {{$lead_note->status_is ? $lead_note->status_is->status_name :''}}</span></h5>
                <h5>{!! $lead_note->note??''!!} </h5>
                <p>Estimated close date: {{date('d M Y', strtotime($lead_note->estimated_close_date))}}</p>
                <p>Confidence: {{$lead_note->confidence??0}}%</p>
                <p>Created by: {!! $lead_note->createdby? $lead_note->createdby->name : ''!!}</p>
                @endif

                <p></p>

                <p class="date-list">{{$noteTime}}</p>
            </div>
        </div>
        @if($lead_note->type == 'noteee')
        <div class="list-two">
            <div class="dropdown">
                <button class="dropdown-toggle" type="button" id="dropdownMenuButton"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="{{url('/').'/'.asset('assets/img')}}/point.svg">

            </button>

            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <div class="dropdown-item" onclick='editNote(@json($lead_note))'>
                    <a>Edit</a>
                </div>
                <div class="dropdown-item">
                    <form action="{{ route('lead-notes.destroy', $lead_note->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Do you really want to delete?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="">
                            delete
                        </button>
                    </form>
                </div>



            </div>
        </div>

    </div>
    @endif
</div>

</div>
</div>
@endforeach
</div>
</div>
<div class="tab-pane fade" id="call-history" role="tabpanel">
    <div class="card-header d-flex justify-content-between align-items-center">
        <p class="hed m-0">Call History</p>
        <span class="call-history-sub">{{ $lead->assign_user ? 'Calls ring '.$lead->assign_user->name.' first' : 'Assign this lead to enable calling' }}</span>
    </div>
    <div class="card-body" id="lead-call-history-body">
        @include('leads.partials.call-history')
    </div>
</div>

<div class="tab-pane fade" id="notes" role="tabpanel">
    <div class="card-header">
        <p class="hed m-0">Notes</p>
    </div>
    <div class="card-body">

    <div class="mb-3">
                <form method="POST" 
                action="{{ route('lead-notes.store') }}" class="form-horizontal" id="frmLeadNotesAdd" enctype="multipart/form-data" style="display:none;">
                @method('POST')
                @csrf
                <input type="hidden" name="lead_id" value="{{$lead->id??''}}">
                <input type="hidden" name="note_id" id="note_id" >
                {!! Form::textarea('note', old('note',''), ['class' => 'form-control rounded border ckeditor-init', 'placeholder' => 'Add a note...','rows'=>8,'id'=>'note_text']) !!}
                <div class="text-end mt-2">
                    <button class="btn btn-sm btn-primary donebtn" type="submit">Done</button>
                </div>
            </form>
        </div>

        @php
        $previousDate1 = null;
        @endphp
        @foreach($lead_notes as $lead_note)
        @php
        $noteDate = \Carbon\Carbon::parse($lead_note->created_at)->format('d M Y'); // e.g., 28 Feb 2024
        $noteTime = \Carbon\Carbon::parse($lead_note->created_at)->format('h:i a'); // e.g., 10:25 pm
        @endphp
        <div>
            @if($noteDate !== $previousDate1)
            <h6 class="text-muted date_listing mt-3"> <img src="{{ url('/').'/'.asset('assets/img/leaddate.svg') }}"> {{ $noteDate }}</h6>
            @php
            $previousDate1 = $noteDate;
            @endphp
            @endif
<!-- code 28 feb 2024  -->

<!--new div--->
<div class="card mb-2 p-0">
    <div class="main-div">
        <div class="list-one">
            <div class="image_box">
                <div class="greenbox">
                    <img src="{{url('/').'/'.asset('assets/img')}}/cup.svg">
                </div>
            </div>
            <div class="listdata">
                <h5>{!! $lead_note->note??''!!}</h5>

                <p></p>

                <p class="date-list">{{$noteTime}}</p>
            </div>
        </div>
        <div class="list-two">
            <div class="dropdown">
                <button class="dropdown-toggle" type="button" id="dropdownMenuButton"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="{{url('/').'/'.asset('assets/img')}}/point.svg">

            </button>

            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <div class="dropdown-item">
                    <a onclick='editNote(@json($lead_note))'>Edit</a>
                </div>
                <div class="dropdown-item">
                    <form action="{{ route('lead-notes.destroy', $lead_note->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Do you really want to delete?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="">
                            delete
                        </button>
                    </form>
                </div>



            </div>
        </div>

    </div>
</div>

</div>
</div>
@endforeach
</div>
</div>
<div class="tab-pane fade" id="files" role="tabpanel">
    @php
        $fileUploaders = \App\Models\User::whereIn('id', collect($media_items)->map(fn ($media) => $media->getCustomProperty('uploaded_by'))->filter()->unique())->pluck('name', 'id');
        $fileIcon = function ($media) {
            $ext = strtolower(pathinfo($media->file_name, PATHINFO_EXTENSION));
            return match (true) {
                str_starts_with((string) $media->mime_type, 'image/') => ['image', 'is-image'],
                $ext === 'pdf' => ['picture_as_pdf', 'is-pdf'],
                in_array($ext, ['xls', 'xlsx', 'xlsm', 'csv', 'ods'], true) => ['table_chart', 'is-sheet'],
                in_array($ext, ['doc', 'docx', 'rtf', 'odt', 'txt'], true) => ['description', 'is-doc'],
                in_array($ext, ['ppt', 'pptx', 'odp'], true) => ['slideshow', 'is-slides'],
                in_array($ext, ['zip', 'rar', '7z'], true) => ['folder_zip', 'is-zip'],
                str_starts_with((string) $media->mime_type, 'audio/') => ['audiotrack', 'is-media'],
                str_starts_with((string) $media->mime_type, 'video/') => ['movie', 'is-media'],
                default => ['insert_drive_file', 'is-other'],
            };
        };
        $fileSize = fn ($bytes) => $bytes >= 1048576 ? number_format($bytes / 1048576, 1).' MB' : number_format(max(1, $bytes / 1024), 0).' KB';
    @endphp
    <div class="card-header d-flex justify-content-between align-items-center">
        <p class="hed m-0">Files</p>
        <span class="call-history-sub">{{ count($media_items) }} file{{ count($media_items) === 1 ? '' : 's' }}</span>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('leads.uploadleadFiles') }}" id="frmLeadfileUpload" enctype="multipart/form-data" class="lead-files-form">
            @csrf
            <input type="hidden" name="lead_id" value="{{ $lead->id }}">
            <label class="lead-dropzone" id="leadDropzone">
                <input type="file" name="lead_files[]" id="leadFilesInput" multiple>
                <i class="material-icons">cloud_upload</i>
                <strong>Drop files here or <span>browse</span></strong>
                <small>Documents, images, sheets, slides, PDFs, ZIP, audio and video · up to 25 MB each · max 20 at once</small>
            </label>
            <ul class="lead-file-queue" id="leadFileQueue"></ul>
            @if($errors->has('lead_files') || $errors->has('lead_files.*') || $errors->has('lead_file'))
            <p class="lead-file-error">{{ $errors->first('lead_files') ?: ($errors->first('lead_files.*') ?: $errors->first('lead_file')) }}</p>
            @endif
            <div class="lead-file-actions" id="leadFileActions" style="display:none">
                <button type="button" class="btn btn-outline-primary" id="leadFilesClear">Clear</button>
                <button type="submit" class="btn btn-primary" id="leadFilesUpload"><i class="material-icons" style="font-size:16px;vertical-align:middle">upload</i> Upload</button>
            </div>
        </form>

        @if(count($media_items) === 0)
        <p class="lead-empty">No files yet.</p>
        @else
        <ul class="lead-file-list">
            @foreach($media_items as $media)
            @php
                [$iconName, $iconClass] = $fileIcon($media);
                $displayName = $media->getCustomProperty('original_name') ?: $media->file_name;
                $uploaderId = $media->getCustomProperty('uploaded_by');
            @endphp
            @php
                $fileUrl = route('leads.files.view', [$lead->id, $media->id]);
                $downloadUrl = $fileUrl.'?download=1';
                $mime = (string) $media->mime_type;
                $ext = strtolower(pathinfo($media->file_name, PATHINFO_EXTENSION));
                $previewKind = match (true) {
                    str_starts_with($mime, 'image/') => 'image',
                    $ext === 'pdf' => 'pdf',
                    str_starts_with($mime, 'video/') => 'video',
                    str_starts_with($mime, 'audio/') => 'audio',
                    in_array($ext, ['txt', 'csv'], true) => 'text',
                    default => 'none',
                };
            @endphp
            <li class="lead-file-item">
                @if($previewKind === 'image')
                <button type="button" class="lead-file-thumb lead-file-preview" data-url="{{ $fileUrl }}" data-download="{{ $downloadUrl }}" data-kind="{{ $previewKind }}" data-name="{{ $displayName }}"><img src="{{ $fileUrl }}" alt="" loading="lazy"></button>
                @else
                <button type="button" class="lead-file-icon {{ $iconClass }} lead-file-preview" data-url="{{ $fileUrl }}" data-download="{{ $downloadUrl }}" data-kind="{{ $previewKind }}" data-name="{{ $displayName }}"><i class="material-icons">{{ $iconName }}</i></button>
                @endif
                <div class="lead-file-main">
                    <button type="button" class="lead-file-name lead-file-preview" data-url="{{ $fileUrl }}" data-download="{{ $downloadUrl }}" data-kind="{{ $previewKind }}" data-name="{{ $displayName }}" title="{{ $displayName }}">{{ $displayName }}</button>
                    <span class="lead-file-meta">
                        {{ strtoupper($ext) }} · {{ $fileSize((int) $media->size) }} · {{ $media->created_at?->format('d M Y, h:i A') }}
                        @if($uploaderId && isset($fileUploaders[$uploaderId]))
                        · by {{ $fileUploaders[$uploaderId] }}
                        @endif
                    </span>
                </div>
                <div class="lead-file-buttons">
                    <button type="button" class="contact-icon-btn lead-file-preview" data-url="{{ $fileUrl }}" data-download="{{ $downloadUrl }}" data-kind="{{ $previewKind }}" data-name="{{ $displayName }}" title="View" data-toggle="tooltip"><i class="material-icons">visibility</i></button>
                    <a href="{{ $downloadUrl }}" class="contact-icon-btn" title="Download" data-toggle="tooltip"><i class="material-icons">download</i></a>
                    <a href="{{ route('leads-deleteMedia') }}?media_id={{ $media->id }}" class="contact-icon-btn contact-icon-muted contact-icon-danger" title="Remove file" data-toggle="tooltip"
                        onclick="return confirm('Remove “{{ addslashes($displayName) }}” from this lead?')"><i class="material-icons">delete</i></a>
                </div>
            </li>
            @endforeach
        </ul>
        @endif
    </div>
</div>
@include('leads.partials.drafts-pane', ['paneId' => 'email-drafts', 'type' => 'email', 'title' => 'Email draft', 'icon' => 'mail', 'placeholder' => "Subject: ...\n\nHi ...,", 'drafts' => $email_drafts])
@include('leads.partials.drafts-pane', ['paneId' => 'linkedin-drafts', 'type' => 'linkedin', 'title' => 'LinkedIn draft', 'icon' => 'work', 'placeholder' => 'Hi ..., I came across ...', 'drafts' => $linkedin_drafts])
<div class="tab-pane fade" id="tasks" role="tabpanel">

    <div class="card-header">
        <p class="hed m-0"> Task</p>
    </div>
    <div class="card-body">
        @if($lead_tasks->isEmpty())
        <p class="lead-empty">No tasks yet.</p>
        @endif
        <ul class="list-group list-group-flush list-task contact-list lead-task-list">
            @foreach($lead_tasks as $lead_task)
            @include('leads.partials.task-item')
            @endforeach
        </ul>
    </div>
</div>
</div>
</div>

<!--- end new tab---->
</div>


</div>
</div>
</div>
<!--  -->



</div>
</div>
</div>
</div>


<div class="modal fade" id="updateLeadModel" role="dialog">
    <div class="modal-dialog">

<!-- Modal content-->
<form method="POST" 
action="{{ route('leads.update',$lead) }}" class="form-horizontal" id="frmLeadsUpdate" enctype="multipart/form-data">
@method('PUT')
@csrf
<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Lead</h4>
    </div>


    <div class="modal-body">
        <div class="row">
            <div class="col-md-12 pr-1 pl-1">
                <div class="col-md-12 form-group">
                    <label for="company_name">Name <span style="color:red">*</span></label>
                    <input type="text"  name="company_name" id="company_name" value="{{ old('company_name',$lead->company_name??'') }}"  class="form-control" placeholder="Name">
                    @if($errors->has('company_name'))
                    <p class="help-block">
                        <strong>{{ $errors->first('company_name') }}</strong>
                    </p>
                    @endif
                </div>
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger mr-2" data-dismiss="modal">Canel</button>
        <button type="submit" class="btn btn-default">Save</button>
    </div>
</div>
</form>
</div>
</div>

<div class="modal fade" id="AddAddressModel" role="dialog">
    <div class="modal-dialog">

<!-- Modal content-->
<form method="POST" 
action="{{ route('leads.storeAddress') }}" class="form-horizontal" id="frmLeadsAddress" enctype="multipart/form-data">
@method('POST')
@csrf
<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Address</h4>
    </div>


    <div class="modal-body">
        <div class="row">
            <div class="col-md-12 pr-1 pl-1">
                <div class="col-md-12 form-group">
                    <label for="address1">Address <span style="color:red">*</span></label>
                    <input type="hidden" name="lead_id" value="{{$lead->id??''}}">
                    <input type="hidden" name="address_id" value="{{$address->id??''}}">
                    <input type="text" name="address1" class="form-control" value="{!! old( 'address1',$address->address1??'' ) !!}" maxlength="200" required>
                    @if ($errors->has('address1'))
                    <div class="error col-lg-12"><p class="text-danger">{{ $errors->first('address1') }}</p></div>
                    @endif
                </div>
                <div class="col-md-12 form-group">
                    <label for="address2">Address2</label>
                    <input type="text" name="address2" class="form-control" value="{!! old( 'address2',$address->address2??'' ) !!}" maxlength="200" >
                    @if ($errors->has('address2'))
                    <div class="error col-lg-12"><p class="text-danger">{{ $errors->first('address2') }}</p></div>
                    @endif
                </div>
                <div class="col-md-12 form-group">
                    <label for="country">Country <span style="color:red">*</span></label>
                    <select class="form-control select2 country" name="country_id" onchange="getStateList()" style="width: 100%;" id="country_id">
                        <option value="">Select {!! trans('panel.global.country') !!}</option>
                        @if(@isset($countries ))
                        @foreach($countries as $country)
                        <option value="{!! $country['id'] !!}" @if(isset($address) && $address->country_id==$country['id']) selected @endif >{!! $country['country_name'] !!}</option>
                        @endforeach
                        @endif
                    </select>
                    @if ($errors->has('country_id'))
                    <div class="error col-lg-12"><p class="text-danger">{{ $errors->first('country_id') }}</p></div>
                    @endif
                </div>
                <div class="col-md-12 form-group">
                    <label for="state_id">State <span style="color:red">*</span></label>
                    <select class="form-control select2 state" name="state_id" id="state_id" onchange="getDistrictList()" style="width: 100%;">
                        @if(isset($address) && $address->state_id)
                        <option value="{!!  $address->state_id !!}">{!! $address->statename->state_name??'' !!}</option>
                        @else
                        <option value="">Select {!! trans('panel.global.state') !!}</option>
                        @endif
                    </select>
                    @if ($errors->has('state_id'))
                    <div class="error col-lg-12"><p class="text-danger">{{ $errors->first('state_id') }}</p></div>
                    @endif
                </div>
                <div class="col-md-12 form-group">
                    <label for="district_id">District </label>
                    <select class="form-control select2 district" name="district_id" id="district_id" onchange="getCityList()" style="width: 100%;">
                        @if(isset($address) && $address->district_id)
                        <option value="{!!  $address->district_id !!}">{!! $address->cityname->city_name??'' !!}</option>
                        @else
                        <option value="">Select {!! trans('panel.global.district') !!}</option>
                        @endif

                    </select>
                    @if ($errors->has('district_id'))
                    <div class="error col-lg-12"><p class="text-danger">{{ $errors->first('district_id') }}</p></div>
                    @endif
                </div>
                <div class="col-md-12 form-group">
                    <label for="city_id">City <span style="color:red">*</span></label>
                    <select class="form-control select2 city" name="city_id" id="city_id" onchange="getPincodeList()" style="width: 100%;">
                        @if(isset($address) && $address->city_id)
                        <option value="{!!  $address->city_id !!}">{!! $address->cityname->city_name??'' !!}</option>
                        @else
                        <option value="">Select {!! trans('panel.global.city') !!}</option>
                        @endif
                    </select>
                    @if ($errors->has('city_id'))
                    <div class="error col-lg-12"><p class="text-danger">{{ $errors->first('city_id') }}</p></div>
                    @endif
                </div>
                <div class="col-md-12 form-group">
                    <label for="pincode_id">pincode <span style="color:red">*</span></label>
                    <select class="form-control pincode select2" name="pincode_id" id="pincode_id" onchange="getAddressData()" style="width: 100%;">
                        <option value="">Select {!! trans('panel.global.pincode') !!}</option>
                        @if(@isset($pincodes ))
                        @foreach($pincodes as $pincode)
                        <option value="{!! $pincode['id'] !!}" @if(isset($address) && $address->pincode_id==$pincode['id']) selected @endif >{!! $pincode['pincode'] !!}</option>
                        @endforeach
                        @endif
                    </select>
                    @if ($errors->has('pincode_id'))
                    <div class="error col-lg-12"><p class="text-danger">{{ $errors->first('pincode_id') }}</p></div>
                    @endif
                </div>

            </div>
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger mr-2" data-dismiss="modal">Canel</button>
        <button type="submit" class="btn btn-default">Save</button>
    </div>
</div>
</form>
</div>
</div>

<script>
    const rangeInput = document.getElementById("confidence");
    const confidenceValue = document.getElementById("confidenceValue");

    rangeInput.addEventListener("input", function () {
        confidenceValue.textContent = this.value + "%";
    });
</script>


<script>
    function toggleCardBody() {
        const body = document.getElementById("cardBody");
        body.style.display = (body.style.display === "none") ? "block" : "none";
    }

    function toggleCardBody1() {
        const body = document.getElementById("cardBody1");
        body.style.display = (body.style.display === "none") ? "block" : "none";
    }

    function toggleCardBody2() {
        const body = document.getElementById("cardBody2");
        body.style.display = (body.style.display === "none") ? "block" : "none";
    }



</script>

<script src="{{ url('/').'/'.asset('assets/js/jquery.custom.js') }}"></script>
<!-- Load jQuery and moment.js -->

<script type="text/javascript" src="{{ url('/').'/'.asset('vendor/ckeditor/js/ckeditor.js') }}"></script>


<div class="modal fade" id="filePreviewModal" tabindex="-1" role="dialog" aria-labelledby="filePreviewTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content fpv-card">
            <div class="fpv-head">
                <i class="material-icons fpv-head-icon">insert_drive_file</i>
                <h5 id="filePreviewTitle" class="fpv-title"></h5>
                <a href="#" class="fpv-btn" id="filePreviewOpen" target="_blank" rel="noopener" title="Open in new tab"><i class="material-icons">open_in_new</i></a>
                <a href="#" class="fpv-btn" id="filePreviewDownload" title="Download"><i class="material-icons">download</i></a>
                <button type="button" class="fpv-btn" data-dismiss="modal" aria-label="Close"><i class="material-icons">close</i></button>
            </div>
            <div class="fpv-body" id="filePreviewBody"></div>
        </div>
    </div>
</div>
@php
    // Contacts with a usable email address, for "Send email" on approved drafts.
    $emailRecipients = $lead_contacts->filter(fn ($contact) => filter_var(trim((string) $contact->email), FILTER_VALIDATE_EMAIL))->values();
@endphp
<div class="modal fade" id="sendDraftModal" tabindex="-1" role="dialog" aria-labelledby="sendDraftTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content sdm-card">
            <div class="sdm-head">
                <div class="sdm-head-icon"><i class="material-icons">send</i></div>
                <div class="sdm-head-copy">
                    <h5 id="sendDraftTitle">Send email</h5>
                    <p>Pick the recipients and the app to send from.</p>
                </div>
                <button type="button" class="sdm-close" data-dismiss="modal" aria-label="Close"><i class="material-icons">close</i></button>
            </div>

            <div class="sdm-body">
                @if($emailRecipients->isEmpty())
                <div class="sdm-empty">
                    <i class="material-icons">contact_mail</i>
                    <p>None of this lead's contacts has an email address yet. Add an email to a contact first.</p>
                </div>
                @else
                <section class="sdm-section">
                    <div class="sdm-section-head">
                        <span class="sdm-step">1</span>
                        <span class="sdm-section-title">To</span>
                        <span class="sdm-count" id="sendDraftCount"></span>
                        <button type="button" class="sdm-link" id="sendDraftAll">Select all</button>
                    </div>
                    <div class="sdm-recipients">
                        @foreach($emailRecipients as $recipient)
                        <label class="sdm-recipient">
                            <input type="checkbox" class="send-draft-to" value="{{ trim($recipient->email) }}" {{ $loop->first ? 'checked' : '' }}>
                            <span class="sdm-check"><i class="material-icons">check</i></span>
                            <span class="sdm-avatar">{{ strtoupper(collect(preg_split('/\s+/', trim($recipient->name ?? '')))->filter()->take(2)->map(fn ($part) => mb_substr($part, 0, 1))->implode('')) ?: '?' }}</span>
                            <span class="sdm-recipient-copy">
                                <span class="sdm-name">{{ $recipient->name }}</span>
                                <span class="sdm-email">{{ strtolower(trim($recipient->email)) }}</span>
                                @if($recipient->title)
                                <span class="sdm-title">{{ $recipient->title }}</span>
                                @endif
                            </span>
                        </label>
                        @endforeach
                    </div>
                </section>

                <section class="sdm-section">
                    <div class="sdm-section-head">
                        <span class="sdm-step">2</span>
                        <span class="sdm-section-title">Subject</span>
                    </div>
                    <div class="sdm-subject" id="sendDraftSubject"></div>
                </section>

                <section class="sdm-section">
                    <div class="sdm-section-head">
                        <span class="sdm-step">3</span>
                        <span class="sdm-section-title">Send using</span>
                    </div>
                    <div class="sdm-apps">
                        @foreach([
                            ['gmail', 'alternate_email', 'Gmail', 'Opens Gmail in a new tab'],
                            ['outlook_work', 'business', 'Outlook', 'Office / Microsoft 365'],
                            ['outlook_personal', 'person', 'Outlook.com', 'Personal account'],
                            ['yahoo', 'mail_outline', 'Yahoo Mail', 'Opens in a new tab'],
                            ['default', 'computer', 'Mail app', 'Default app on this computer'],
                            ['copy', 'content_copy', 'Copy', 'Paste into any app'],
                        ] as [$appValue, $appIcon, $appName, $appHint])
                        <label class="sdm-app">
                            <input type="radio" name="send_draft_app" value="{{ $appValue }}" {{ $loop->first ? 'checked' : '' }}>
                            <span class="sdm-app-card">
                                <i class="material-icons">{{ $appIcon }}</i>
                                <span class="sdm-app-name">{{ $appName }}</span>
                                <span class="sdm-app-hint">{{ $appHint }}</span>
                            </span>
                        </label>
                        @endforeach
                    </div>
                </section>
                @endif
                <p class="sdm-error" id="sendDraftError"></p>
            </div>

            <div class="sdm-foot">
                <button type="button" class="sdm-btn" data-dismiss="modal">Cancel</button>
                @if($emailRecipients->isNotEmpty())
                <button type="button" class="sdm-btn is-primary" id="sendDraftGo"><i class="material-icons">send</i><span>Open &amp; send</span></button>
                @endif
            </div>
        </div>
    </div>
</div>
@php
    // Contacts with a LinkedIn profile link, for "Send message" on approved LinkedIn drafts.
    $linkedinRecipients = $lead_contacts->map(function ($contact) {
        $url = trim((string) $contact->url);
        if ($url !== '' && ! preg_match('#^https?://#i', $url)) {
            $url = 'https://' . ltrim($url, '/');
        }
        $contact->linkedin_url = filter_var($url, FILTER_VALIDATE_URL) && str_contains(strtolower($url), 'linkedin.com') ? $url : null;
        return $contact;
    })->filter(fn ($contact) => $contact->linkedin_url)->values();
@endphp
<div class="modal fade" id="sendLinkedinModal" tabindex="-1" role="dialog" aria-labelledby="sendLinkedinTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content sdm-card">
            <div class="sdm-head">
                <div class="sdm-head-icon sdm-head-icon-li"><i class="material-icons">work</i></div>
                <div class="sdm-head-copy">
                    <h5 id="sendLinkedinTitle">Send LinkedIn message</h5>
                    <p>Pick one contact. The message is copied and their LinkedIn profile opens.</p>
                </div>
                <button type="button" class="sdm-close" data-dismiss="modal" aria-label="Close"><i class="material-icons">close</i></button>
            </div>

            <div class="sdm-body">
                @if($linkedinRecipients->isEmpty())
                <div class="sdm-empty">
                    <i class="material-icons">link_off</i>
                    <p>None of this lead's contacts has a LinkedIn profile link. Add the LinkedIn URL to a contact first.</p>
                </div>
                @else
                <section class="sdm-section">
                    <div class="sdm-section-head">
                        <span class="sdm-step">1</span>
                        <span class="sdm-section-title">Send to</span>
                        <span class="sdm-count">Choose one</span>
                    </div>
                    <div class="sdm-recipients">
                        @foreach($linkedinRecipients as $recipient)
                        <label class="sdm-recipient sdm-recipient-radio {{ $loop->first ? 'is-checked' : '' }}">
                            <input type="radio" name="linkedin_recipient" class="send-linkedin-to" value="{{ $recipient->linkedin_url }}" data-name="{{ $recipient->name }}" {{ $loop->first ? 'checked' : '' }}>
                            <span class="sdm-check sdm-radio"><i class="material-icons">check</i></span>
                            <span class="sdm-avatar">{{ strtoupper(collect(preg_split('/\s+/', trim($recipient->name ?? '')))->filter()->take(2)->map(fn ($part) => mb_substr($part, 0, 1))->implode('')) ?: '?' }}</span>
                            <span class="sdm-recipient-copy">
                                <span class="sdm-name">{{ $recipient->name }}</span>
                                <span class="sdm-email">{{ preg_replace('#^https?://(www\.)?#i', '', rtrim($recipient->linkedin_url, '/')) }}</span>
                                @if($recipient->title)
                                <span class="sdm-title">{{ $recipient->title }}</span>
                                @endif
                            </span>
                        </label>
                        @endforeach
                    </div>
                </section>

                <section class="sdm-section">
                    <div class="sdm-section-head">
                        <span class="sdm-step">2</span>
                        <span class="sdm-section-title">Message</span>
                    </div>
                    <div class="sdm-subject sdm-message" id="sendLinkedinPreview"></div>
                </section>

                <div class="sdm-note">
                    <i class="material-icons">info</i>
                    <span>LinkedIn does not let other websites type into its chat. After the profile opens, click <strong>Message</strong> and paste (Ctrl+V / Cmd+V).</span>
                </div>
                @endif
                <p class="sdm-error" id="sendLinkedinError"></p>
            </div>

            <div class="sdm-foot">
                <button type="button" class="sdm-btn" data-dismiss="modal">Cancel</button>
                @if($linkedinRecipients->isNotEmpty())
                <button type="button" class="sdm-btn is-primary is-linkedin" id="sendLinkedinGo"><i class="material-icons">content_copy</i><span>Copy &amp; open LinkedIn</span></button>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="callFeedbackModal" tabindex="-1" role="dialog" aria-labelledby="callFeedbackTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content cfm-card">
            <form id="callFeedbackForm" class="cfm-form">
                <div class="cfm-head">
                    <div class="cfm-head-icon"><i class="material-icons">phone_in_talk</i></div>
                    <div class="cfm-head-copy">
                        <h5 id="callFeedbackTitle">How did the call go?</h5>
                        <p id="callFeedbackSub"></p>
                    </div>
                    <button type="button" class="cfm-close" data-dismiss="modal" aria-label="Close"><i class="material-icons">close</i></button>
                </div>

                <div class="cfm-body">
                    <input type="hidden" id="callFeedbackUrl">

                    <div class="cfm-section">
                        <div class="cfm-label">Call status <span class="cfm-req">*</span></div>
                        @if($call_statuses->isNotEmpty())
                        @php
                            $outcomeStyle = [
                                'lead_call_done' => ['check_circle', 'is-green'],
                                'lead_call_no_response' => ['phone_missed', 'is-amber'],
                                'lead_call_switch_off' => ['phone_disabled', 'is-grey'],
                                'lead_call_wrong_number' => ['error_outline', 'is-red'],
                                'lead_call_back' => ['event', 'is-blue'],
                            ];
                        @endphp
                        <div class="cfm-outcomes">
                            @foreach($call_statuses as $callStatus)
                            @php([$outcomeIcon, $outcomeTone] = $outcomeStyle[$callStatus->status_name] ?? ['label', 'is-grey'])
                            <label class="cfm-outcome {{ $outcomeTone }}">
                                <input type="radio" name="call_status" value="{{ $callStatus->id }}" data-callback="{{ $callStatus->status_name === \App\Models\Status::LEAD_CALL_BACK ? 1 : 0 }}" data-done="{{ $callStatus->status_name === \App\Models\Status::LEAD_CALL_DONE ? 1 : 0 }}">
                                <span><i class="material-icons">{{ $outcomeIcon }}</i>{{ $callStatus->display_name ?: $callStatus->status_name }}</span>
                            </label>
                            @endforeach
                        </div>
                        @else
                        <p class="cfm-hint">No call statuses are set up yet.</p>
                        @endif
                    </div>

                    <div class="cfm-section cfm-details" id="callFeedbackDetails" style="display:none">
                        <div class="cfm-label">Customer wants details on <span class="cfm-req">*</span></div>
                        <div class="cfm-channels">
                            <label class="cfm-channel is-whatsapp"><input type="checkbox" name="details_via[]" value="whatsapp"><span><i class="material-icons">chat</i>WhatsApp</span></label>
                            <label class="cfm-channel is-linkedin"><input type="checkbox" name="details_via[]" value="linkedin"><span><i class="material-icons">work</i>LinkedIn</span></label>
                            <label class="cfm-channel is-email"><input type="checkbox" name="details_via[]" value="email"><span><i class="material-icons">mail</i>Email</span></label>
                            <label class="cfm-channel is-none"><input type="checkbox" name="details_via[]" value="none"><span><i class="material-icons">block</i>No follow-up needed</span></label>
                        </div>
                    </div>

                    <div class="cfm-section cfm-callback" id="callFeedbackCallback" style="display:none">
                        <div class="cfm-label"><span id="callFeedbackFollowUpLabel">Call back on</span> <span class="cfm-req">*</span></div>
                        <input type="datetime-local" class="cfm-input" id="callFeedbackFollowUp">
                        <p class="cfm-hint" id="callFeedbackFollowUpHint">A "Call back" task is created for this time.</p>
                    </div>

                    <div class="cfm-section">
                        <div class="cfm-label">Lead type</div>
                        <div class="cfm-select-wrap">
                            <select class="cfm-input" id="callFeedbackStatus" name="status">
                                <option value="0" {{ (int) $lead->status === 0 ? 'selected' : '' }}>Pending</option>
                                @foreach($status as $stat)
                                <option value="{{ $stat['id'] }}" {{ (int) $lead->status === (int) $stat['id'] ? 'selected' : '' }}>{{ $stat['display_name'] }}</option>
                                @endforeach
                            </select>
                            <i class="material-icons">expand_more</i>
                        </div>
                        <p class="cfm-hint">Keep the current lead type or change it.</p>
                    </div>

                    <div class="cfm-section">
                        <div class="cfm-label">Remark <span class="cfm-req">*</span></div>
                        <textarea class="cfm-input cfm-textarea" id="callFeedbackRemark" name="remark" rows="3" maxlength="1000" placeholder="What was discussed on the call?"></textarea>
                    </div>
                    <p class="cfm-error" id="callFeedbackError"></p>
                </div>

                <div class="cfm-foot">
                    <button type="button" class="cfm-btn" data-dismiss="modal">Later</button>
                    <button type="submit" class="cfm-btn is-primary" id="callFeedbackSave"><i class="material-icons">check</i><span>Save</span></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

    jQuery(document).ready(function(){

        
        $('#task_date').on('focusout', function() {
          $(this).css('z-index', '');
        }); 
        $('#estimated_close_date').on('focusout', function() {
          $(this).css('z-index', '');
        });

        jQuery('#frmLeadContactsCreate').validate({
            rules: {
                name: {
                    required: true
                },
                title: {
                    required: true
                },
                phone_number: {
                    required: true,
                    number: true
                },
                contact_email: {
//required: true,
                    email:true
                },
// url: {
//     required: true
// },             
            }

        });

        jQuery('#frmLeadTaskCreate').validate({
            rules: {
                assigned_to: {
                    required: true
                },
                description: {
                    required: true
                },
                date: {
                    required: true
                },
                // time: {
                //     required: true
                // }           
            }

        });


        jQuery('#frmLeadsUpdate').validate({
            rules: {
                company_name: {
                    required: true
                },
            }
        });

        jQuery('#frmLeadNotesAdd').validate({
            ignore: [],
            rules: {
                note: {
                    required: true
                },
            }
        });

        jQuery('#frmLeadOpportunitiesCreate').validate({
            rules: {
                assigned_to: {
                    required: true
                },
                lead_contact_id: {
                    required: true
                },
                note: {
                    required: true
                },
amount: {
    required: true,
    number:true
},
// type: {
//     required: true
// },
                confidence: {
                    required: true,
                    number:true,
                    max: 100,
                    min: 0
                },
                estimated_close_date: {
                    required: true
                }, 
                status: {
                    required: true
                },
            }
        });

        jQuery('#frmLeadsAddress').validate({
            rules: {
                address1: {
                    required: true
                },
// address2: {
//     required: true
// },
                country_id: {
                    required: true
                },
                state_id: {
                    required: true
                },
// district_id: {
//     required: true
// },
                city_id: {
                    required: true
                },
                pincode_id: {
                    required: true
                },
            }
        });




// 1. Initialize CKEditor for all matching elements
        document.querySelectorAll('.ckeditor-init').forEach(function (item) {
            var editor = CKEDITOR.replace(item, {
                customConfig: 'config.js',
                toolbar: 'Basic',
                height: '15em'
            });

// 2. Update the textarea content on change (already good)
            editor.on('change', function () {
                this.updateElement();
            });
        });


    });

    function createTask(){
        $('#task_id').val('');
        $('#frmLeadTaskCreate :input:not(:button, [type="hidden"])').val('');
        $('#frmLeadTaskCreate #assigned_to').change();
        $('#frmLeadTaskCreate #priority').change();
        $('#frmLeadTaskCreate #status').val('pending');
        $('#frmLeadTaskCreate #status').change();
        $('#frmLeadTaskCreate').show();
    }
    function editTask(task_data){

        if (typeof task_data === "string") {
            task_data = JSON.parse(task_data);
        }

        $('#frmLeadTaskCreate').show();
console.log(task_data);
        $('#frmLeadTaskCreate #assigned_to').val(task_data.assigned_to);
        $('#frmLeadTaskCreate #assigned_to').change();
        $('#frmLeadTaskCreate #description').val(task_data.description);
        $('#frmLeadTaskCreate #priority').val(task_data.priority);
        $('#frmLeadTaskCreate #priority').change();
        $('#frmLeadTaskCreate #status').val(task_data.status);
        $('#frmLeadTaskCreate #status').change();
        $('#frmLeadTaskCreate #task_date').val(task_data.date);
        $('#frmLeadTaskCreate #time').val(task_data.time);
        $('#frmLeadTaskCreate #task_id').val(task_data.id);


    }

    function createContact(){
        $('#contact_id').val('');
        $('#frmLeadContactsCreate :input:not(:button, [type="hidden"])').val('');
        $('#frmLeadContactsCreate').show();
    }

    function editContact(contact_data){

        if (typeof contact_data === "string") {
            contact_data = JSON.parse(contact_data);
        }

        $('#frmLeadContactsCreate').show();
        $('#contact_id').val(contact_data.id);
        $('#frmLeadContactsCreate #name').val(contact_data.name);
        $('#frmLeadContactsCreate #title').val(contact_data.title);
        $('#frmLeadContactsCreate #phone_number').val(contact_data.phone_number);
        $('#frmLeadContactsCreate #contact_email').val(contact_data.email);
        $('#frmLeadContactsCreate #url').val(contact_data.url);
    }


    function createOpportunity(){
        $('#opportunity_id').val('');
        $('#frmLeadOpportunitiesCreate :input:not(:button, [type="hidden"])').val('');
        $('#frmLeadOpportunitiesCreate #o_amount').val(0);
        $('#frmLeadOpportunitiesCreate').show();
        $('#frmLeadOpportunitiesCreate #o_assigned_to').change();
        $('#frmLeadOpportunitiesCreate #lead_contact_id').change();
        var firstVal = $('#frmLeadOpportunitiesCreate #status option:first').val();
        $('#frmLeadOpportunitiesCreate #status').val(firstVal).change();
//var firstVal = $('#frmLeadOpportunitiesCreate #type option:first').val();
//$('#frmLeadOpportunitiesCreate #type').val(firstVal).change();

    }

    function editOpportunity(opportunity_data){

        if (typeof opportunity_data === "string") {
            opportunity_data = JSON.parse(opportunity_data);
        }
        console.log(opportunity_data);

        $('#opportunity_id').val(opportunity_data.id);
        $('#frmLeadOpportunitiesCreate #o_assigned_to').val(opportunity_data.assigned_to);
        $('#frmLeadOpportunitiesCreate #lead_contact_id').val(opportunity_data.lead_contact_id);
        $('#frmLeadOpportunitiesCreate #o_assigned_to').change();
        $('#frmLeadOpportunitiesCreate #lead_contact_id').change();
        $('#frmLeadOpportunitiesCreate #o_note').val(opportunity_data.note);
$('#frmLeadOpportunitiesCreate #o_amount').val(opportunity_data.amount);
        $('#frmLeadOpportunitiesCreate #confidence').val(opportunity_data.confidence);
        $('#frmLeadOpportunitiesCreate #confidenceValue').html(opportunity_data.confidence+"%");
        $('#frmLeadOpportunitiesCreate #estimated_close_date').val(opportunity_data.estimated_close_date);
        $('#frmLeadOpportunitiesCreate #status').val(opportunity_data.status);
        $('#frmLeadOpportunitiesCreate #status').change();
//$('#frmLeadOpportunitiesCreate #type').val(opportunity_data.type);
// $('#frmLeadOpportunitiesCreate #type').change();
        $('#frmLeadOpportunitiesCreate').show();


    }

    function editNote(note){

        console.log(note);
        if (typeof note === "string") {
            note = JSON.parse(note, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
            console.log(note);
        }

        $('#frmLeadNotesAdd').show();
        $('#note_id').val(note.id);
        $('#note_text').val(note.note);

// Set value in CKEditor
        if (CKEDITOR.instances['note_text']) {
            CKEDITOR.instances['note_text'].setData(note.note);
        } else {
            $('#note_text').val(note.note);
        }
    }

    function openAddAddressModel(){

        $('#country_id').select2({
            dropdownParent: $('#AddAddressModel')
        });
        $('#state_id').select2({
            dropdownParent: $('#AddAddressModel')
        });
        $('#district_id').select2({
            dropdownParent: $('#AddAddressModel')
        });
        $('#city_id').select2({
            dropdownParent: $('#AddAddressModel')
        });
        // Search pincodes on demand instead of rendering every pincode.
        $('#pincode_id').select2({
            dropdownParent: $('#AddAddressModel'),
            placeholder: 'Search pincode',
            minimumInputLength: 3,
            ajax: {
                url: "{{ url('getPincodeSearch') }}",
                dataType: 'json',
                delay: 300,
                data: function (params) { return { q: params.term, page: params.page || 1 }; },
                processResults: function (data) {
                    return {
                        results: (data.results || []).map(function (item) { return { id: item.id, text: item.pincode }; }),
                        pagination: { more: data.pagination && data.pagination.more }
                    };
                }
            }
        });
    }

    $('#status').on('change', function () {
    // alert(this.value);
    var status = this.value;
    Swal.fire({
        title: 'Are you sure?',
        text: "",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, change it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: "{{ route('leads.changeStatus') }}",
                method: 'POST',
                data: {
                    lead_id: '{{$lead->id}}',
                    status: status,
                    _token: '{{ csrf_token() }}'
                },
                success: function (res) {
                    if (res.status == 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: res.message,
                            showConfirmButton: true,
                            timer: 2000
                        })
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: res.message,
                            showConfirmButton: true,
                            timer: 2000
                        })
                    }
                }
            });
        }
    });
});

function activateNotesTab() {
        // Show the form
        $('#frmLeadNotesAdd').show();

        // Activate the Notes tab using Bootstrap's tab API
        $('#notes-tab').tab('show');
    }

    // Click-to-call: confirm, ring the assigned user first, then the contact.
    var leadCallRingNote = @json($lead->assign_user ? $lead->assign_user->name."'s phone will ring first, then the contact." : '');
    $(document).on('click', '.contact-call-btn', function () {
        confirmContactCall($(this));
    });

    function confirmContactCall($btn) {
        var name = $btn.data('contact-name') || 'this contact';
        var phone = $btn.data('contact-phone') || '';
        Swal.fire({
            title: 'Do you really want to call this contact?',
            html: '<strong>' + $('<div>').text(name).html() + '</strong><br>' + $('<div>').text(phone).html()
                + '<br><small>' + $('<div>').text(leadCallRingNote).html() + '</small>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, call',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#0891b2',
            cancelButtonColor: '#d33'
        }).then(function (result) {
            if (!result.value) return;
            $btn.addClass('is-calling');
            $.ajax({
                url: $btn.data('call-url'),
                method: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function (res) {
                    Swal.fire({ icon: 'success', title: 'Call started', text: res.message, timer: 4000 });
                    refreshLeadCallHistory();
                    pollLeadCall(res.data.status_url, $btn, 0, res.data.feedback_url, name + (phone ? ' · ' + phone : ''));
                },
                error: function (xhr) {
                    $btn.removeClass('is-calling');
                    var message = (xhr.responseJSON && xhr.responseJSON.message) || 'Unable to start the call.';
                    Swal.fire({ icon: 'error', title: 'Call failed', text: message });
                }
            });
        });
    }

    function refreshLeadCallHistory() {
        $.get("{{ route('leads.calls.history', $lead->id) }}", function (res) {
            $('#lead-call-history-body').html(res.html);
            $('#call-history-tab .call-history-count').remove();
            if (res.count) $('#call-history-tab').append('<span class="call-history-count">' + res.count + '</span>');
        });
    }

    // Poll every 5s (max ~15 min) so the history updates when the call ends.
    function pollLeadCall(statusUrl, $btn, attempt, feedbackUrl, callLabel) {
        if (attempt > 180) { $btn.removeClass('is-calling'); return; }
        setTimeout(function () {
            $.get(statusUrl, function (res) {
                if (res.data && res.data.completed) {
                    $btn.removeClass('is-calling');
                    refreshLeadCallHistory();
                    if (feedbackUrl) openCallFeedback(feedbackUrl, callLabel);
                } else {
                    pollLeadCall(statusUrl, $btn, attempt + 1, feedbackUrl, callLabel);
                }
            }).fail(function () { $btn.removeClass('is-calling'); });
        }, 5000);
    }

    // Post-call remark + lead status (same as the mobile app).
    function openCallFeedback(url, label) {
        $('#callFeedbackUrl').val(url);
        $('#callFeedbackSub').text(label || '');
        $('#callFeedbackRemark').val('');
        $('input[name="call_status"]').prop('checked', false);
        $('input[name="details_via[]"]').prop('checked', false);
        $('#callFeedbackFollowUp').val('');
        $('#callFeedbackCallback, #callFeedbackDetails').hide();
        $('#callFeedbackError').text('');
        openPageModal('#callFeedbackModal');
        setTimeout(function () { $('#callFeedbackRemark').trigger('focus'); }, 400);
    }

    $(document).on('click', '.call-remark-btn', function () {
        openCallFeedback($(this).data('feedback-url'), $(this).data('call-label'));
    });

    // "Call Back" asks for a follow-up date & time (not in the past).
    function localDateTimeValue(date) {
        var pad = function (n) { return (n < 10 ? '0' : '') + n; };
        return date.getFullYear() + '-' + pad(date.getMonth() + 1) + '-' + pad(date.getDate()) + 'T' + pad(date.getHours()) + ':' + pad(date.getMinutes());
    }

    // Which follow-up (if any) the chosen status needs.
    function callFollowUpMode() {
        var $picked = $('input[name="call_status"]:checked');
        if (!$picked.length) return '';
        if (String($picked.data('callback')) === '1') return 'callback';
        if (String($picked.data('done')) === '1') {
            var channels = $('input[name="details_via[]"]:checked').map(function () { return this.value; }).get()
                .filter(function (value) { return value !== 'none'; });
            return channels.length ? 'details' : '';
        }
        return '';
    }

    function syncCallFollowUp() {
        var $picked = $('input[name="call_status"]:checked');
        $('#callFeedbackDetails').toggle($picked.length > 0 && String($picked.data('done')) === '1');
        var mode = callFollowUpMode();
        $('#callFeedbackCallback').toggle(mode !== '');
        if (mode === 'callback') {
            $('#callFeedbackFollowUpLabel').text('Call back on');
            $('#callFeedbackFollowUpHint').text('A "Call back" task is created for this time.');
        } else if (mode === 'details') {
            $('#callFeedbackFollowUpLabel').text('Send details by');
            $('#callFeedbackFollowUpHint').text('A task to send the details is created for this time.');
        }
        if (mode) {
            var now = new Date();
            $('#callFeedbackFollowUp').attr('min', localDateTimeValue(now));
            if (!$('#callFeedbackFollowUp').val()) {
                $('#callFeedbackFollowUp').val(localDateTimeValue(new Date(now.getTime() + 60 * 60 * 1000)));
            }
        }
    }

    $(document).on('change', 'input[name="call_status"]', function () {
        $('#callFeedbackError').text('');
        syncCallFollowUp();
    });

    // "No follow-up needed" and the channels exclude each other.
    $(document).on('change', 'input[name="details_via[]"]', function () {
        if (this.checked) {
            if (this.value === 'none') $('input[name="details_via[]"]').not(this).prop('checked', false);
            else $('input[name="details_via[]"][value="none"]').prop('checked', false);
        }
        $('#callFeedbackError').text('');
        syncCallFollowUp();
    });

    $('#callFeedbackForm').on('submit', function (event) {
        event.preventDefault();
        var $picked = $('input[name="call_status"]:checked');
        var remark = $.trim($('#callFeedbackRemark').val());
        if ($('input[name="call_status"]').length && !$picked.length) {
            $('#callFeedbackError').text('Please select the call status.');
            return;
        }
        var isCallDone = $picked.length && String($picked.data('done')) === '1';
        var detailsVia = $('input[name="details_via[]"]:checked').map(function () { return this.value; }).get();
        if (isCallDone && !detailsVia.length) {
            $('#callFeedbackError').text('Choose where the customer wants details, or "No follow-up needed".');
            return;
        }
        var mode = callFollowUpMode();
        var followUp = $('#callFeedbackFollowUp').val();
        if (mode && !followUp) {
            $('#callFeedbackError').text(mode === 'callback' ? 'Please choose the call back date and time.' : 'Please choose when to send the details.');
            return;
        }
        if (!remark) {
            $('#callFeedbackError').text('Please enter a remark.');
            return;
        }
        var $save = $('#callFeedbackSave').prop('disabled', true);
        $save.find('span').text('Saving...');
        $.ajax({
            url: $('#callFeedbackUrl').val(),
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                call_status: $picked.val() || '',
                status: $('#callFeedbackStatus').val(),
                remark: remark,
                details_via: isCallDone ? detailsVia : [],
                follow_up_at: mode ? followUp : ''
            },
            success: function (res) {
                $('#callFeedbackModal').modal('hide');
                var next = res && res.next_contact;
                var $nextBtn = next ? $('.contact-call-btn[data-call-url$="/contacts/' + next.contact_id + '/call"]') : $();
                if (!next || !$nextBtn.length) {
                    window.location.reload();
                    return;
                }
                Swal.fire({
                    icon: 'info',
                    title: next.remaining + ' contact' + (next.remaining === 1 ? '' : 's') + ' still to call',
                    html: 'Next: <strong>' + $('<div>').text(next.name || 'Unnamed').html() + '</strong><br>' + $('<div>').text(next.phone || '').html(),
                    showCancelButton: true,
                    confirmButtonText: 'Call ' + $('<div>').text((next.name || 'next contact').split(' ')[0]).html(),
                    cancelButtonText: 'Later',
                    confirmButtonColor: '#0891b2'
                }).then(function (result) {
                    if (result.value) {
                        confirmContactCall($nextBtn);
                    } else {
                        window.location.reload();
                    }
                });
            },
            error: function (xhr) {
                var res = xhr.responseJSON || {};
                var message = res.message || 'Could not save the remark.';
                if (res.errors) message = Object.values(res.errors)[0][0];
                $('#callFeedbackError').text(message);
            },
            complete: function () {
                $save.prop('disabled', false);
                $save.find('span').text('Save');
            }
        });
    });

    // Email / LinkedIn drafts: new, edit, cancel, copy.
    $(document).on('click', '.draft-new-btn', function () {
        var $form = $($(this).data('draft-form'));
        $form.find('.draft-id').val('');
        $form.find('.draft-text').val('');
        $form.find('.draft-approver').val('');
        var $contact = $form.find('.draft-contact');
        $contact.val($contact.find('option[value!=""]').length === 1 ? $contact.find('option[value!=""]').val() : '');
        $form.find('.draft-feedback-banner').hide().empty();
        $form.slideDown(150);
        $form.find('.draft-text').trigger('focus');
    });

    $(document).on('click', '.draft-edit', function () {
        var $form = $($(this).data('draft-form'));
        $form.find('.draft-id').val($(this).data('id'));
        $form.find('.draft-text').val($(this).data('text'));
        $form.find('.draft-approver').val(String($(this).data('approver') || ''));
        $form.find('.draft-contact').val(String($(this).data('contact') || ''));
        // Editing after the approver's feedback: show it while editing.
        var feedback = String($(this).data('feedback') || '');
        var $banner = $form.find('.draft-feedback-banner').empty();
        if (feedback) {
            $banner.append($('<b>').text('Approver feedback: ')).append(document.createTextNode(feedback)).show();
        } else {
            $banner.hide();
        }
        $form.slideDown(150);
        $form.find('.draft-text').trigger('focus');
    });

    $(document).on('click', '.draft-cancel', function () {
        $(this).closest('.draft-form').slideUp(150);
    });

    $(document).on('click', '.draft-copy', function () {
        var $btn = $(this);
        var text = $($btn.data('copy-target')).text();
        var done = function () {
            $btn.find('i').text('check');
            setTimeout(function () { $btn.find('i').text('content_copy'); }, 1500);
        };
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(done);
        } else {
            var $tmp = $('<textarea>').val(text).appendTo('body').select();
            document.execCommand('copy');
            $tmp.remove();
            done();
        }
    });

    // After saving / deleting, reopen the tab the user was working in.
    @if(session('lead_tab'))
    $(function () { $('#{{ session('lead_tab') }}-tab').tab('show'); });
    @endif

    // Popups live inside the layout's .main-panel, whose stacking/scroll context can
    // hide a Bootstrap modal behind the page. Move it to <body> before opening.
    function openPageModal(selector) {
        var $modal = $(selector);
        if (!$modal.length) return;
        if (!$modal.parent().is('body')) $modal.appendTo('body');
        if (typeof $.fn.modal === 'function') {
            $modal.modal('show');
        } else {
            $modal.addClass('show').css('display', 'block').attr('aria-hidden', 'false');
        }
    }

    // Send an approved email draft: pick recipients, then open the mail app / Gmail
    // with the subject ("Subject: ..." first line) and body filled in.
    var sendDraft = { subject: '', body: '' };

    function splitDraft(text) {
        var lines = String(text || '').replace(/\r\n/g, '\n').split('\n');
        var subject = '';
        if (lines.length && /^\s*subject\s*:/i.test(lines[0])) {
            subject = lines.shift().replace(/^\s*subject\s*:\s*/i, '').trim();
            while (lines.length && !lines[0].trim()) lines.shift();
        }
        return { subject: subject, body: lines.join('\n').trim() };
    }

    function selectedRecipients() {
        return $('.send-draft-to:checked').map(function () { return this.value; }).get();
    }

    // Only the email button: the LinkedIn button shares the .draft-send-btn style.
    $(document).on('click', '.draft-email-btn', function () {
        sendDraft = splitDraft($($(this).data('draft-target')).text());
        var draftEmail = String($(this).data('contact-email') || '').toLowerCase();
        if (draftEmail && $('.send-draft-to').filter(function () { return this.value.toLowerCase() === draftEmail; }).length) {
            $('.send-draft-to').each(function () { this.checked = this.value.toLowerCase() === draftEmail; });
        }
        $('#sendDraftSubject').text(sendDraft.subject || 'No subject (start the draft with "Subject: ..." to add one)')
            .toggleClass('is-empty', !sendDraft.subject);
        updateRecipientCount();
        $('#sendDraftError').text('');
        openPageModal('#sendDraftModal');
    });

    function updateRecipientCount() {
        var total = $('.send-draft-to').length;
        var picked = $('.send-draft-to:checked').length;
        $('.send-draft-to').each(function () { $(this).closest('.sdm-recipient').toggleClass('is-checked', this.checked); });
        $('#sendDraftCount').text(picked + ' of ' + total + ' selected');
        $('#sendDraftAll').text(picked === total ? 'Clear all' : 'Select all');
    }

    $('#sendDraftAll').on('click', function () {
        var selectAll = $('.send-draft-to:checked').length !== $('.send-draft-to').length;
        $('.send-draft-to').prop('checked', selectAll);
        updateRecipientCount();
    });

    $(document).on('change', '.send-draft-to', function () {
        updateRecipientCount();
        $('#sendDraftError').text('');
    });

    // Remember the agent's last choice of mail app (per browser).
    try {
        var savedApp = localStorage.getItem('fk_send_draft_app');
        if (savedApp) $('input[name="send_draft_app"][value="' + savedApp + '"]').prop('checked', true);
    } catch (e) {}

    function syncSendLabel() {
        var app = $('input[name="send_draft_app"]:checked').val();
        $('#sendDraftGo span').text(app === 'copy' ? 'Copy email' : 'Open & send');
    }
    $(document).on('change', 'input[name="send_draft_app"]', syncSendLabel);
    syncSendLabel();

    $('#sendDraftGo').on('click', function () {
        var to = selectedRecipients();
        if (!to.length) {
            $('#sendDraftError').text('Select at least one email address.');
            return;
        }
        var app = $('input[name="send_draft_app"]:checked').val() || 'gmail';
        try { localStorage.setItem('fk_send_draft_app', app); } catch (e) {}

        var toList = to.join(',');
        var subject = encodeURIComponent(sendDraft.subject);
        var body = encodeURIComponent(sendDraft.body);
        var webUrls = {
            gmail: 'https://mail.google.com/mail/?view=cm&fs=1&to=' + encodeURIComponent(toList) + '&su=' + subject + '&body=' + body,
            outlook_work: 'https://outlook.office.com/mail/deeplink/compose?to=' + encodeURIComponent(toList) + '&subject=' + subject + '&body=' + body,
            outlook_personal: 'https://outlook.live.com/mail/0/deeplink/compose?to=' + encodeURIComponent(toList) + '&subject=' + subject + '&body=' + body,
            yahoo: 'https://compose.mail.yahoo.com/?to=' + encodeURIComponent(toList) + '&subject=' + subject + '&body=' + body
        };

        if (app === 'copy') {
            var text = 'To: ' + to.join(', ') + '\n' + (sendDraft.subject ? 'Subject: ' + sendDraft.subject + '\n' : '') + '\n' + sendDraft.body;
            var done = function () {
                $('#sendDraftModal').modal('hide');
                Swal.fire({ icon: 'success', title: 'Email copied', text: 'Paste it into any mail app to send.', timer: 2500 });
            };
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(done);
            } else {
                var $tmp = $('<textarea>').val(text).appendTo('body').select();
                document.execCommand('copy');
                $tmp.remove();
                done();
            }
            return;
        }

        if (app === 'default') {
            window.location.href = 'mailto:' + to.map(encodeURIComponent).join(',') + '?subject=' + subject + '&body=' + body;
        } else {
            window.open(webUrls[app], '_blank', 'noopener');
        }
        $('#sendDraftModal').modal('hide');
    });

    // Send an approved LinkedIn draft: pick ONE contact, copy the message and open
    // their LinkedIn profile (LinkedIn does not allow pre-filling its chat).
    var linkedinMessage = '';

    $(document).on('click', '.draft-linkedin-btn', function () {
        linkedinMessage = $.trim($($(this).data('draft-target')).text());
        var draftUrl = String($(this).data('contact-url') || '');
        if (draftUrl) {
            $('.send-linkedin-to').each(function () {
                if (this.value === draftUrl || this.value.replace(/^https?:\/\//i, '').replace(/\/$/, '') === draftUrl.replace(/^https?:\/\//i, '').replace(/\/$/, '')) this.checked = true;
            });
            $('.send-linkedin-to').trigger('change');
        }
        $('#sendLinkedinPreview').text(linkedinMessage);
        $('#sendLinkedinError').text('');
        openPageModal('#sendLinkedinModal');
    });

    $(document).on('change', '.send-linkedin-to', function () {
        $('.send-linkedin-to').each(function () { $(this).closest('.sdm-recipient').toggleClass('is-checked', this.checked); });
        $('#sendLinkedinError').text('');
    });

    $(document).on('click', '#sendLinkedinGo', function () {
        var $picked = $('.send-linkedin-to:checked');
        if (!$picked.length) {
            $('#sendLinkedinError').text('Select a contact.');
            return;
        }
        // Copy synchronously (inside the click) so it still works after the new tab takes focus.
        var $tmp = $('<textarea>').val(linkedinMessage).css({ position: 'fixed', opacity: 0 }).appendTo('body');
        $tmp[0].select();
        var copied = false;
        try { copied = document.execCommand('copy'); } catch (e) {}
        $tmp.remove();
        if (!copied && navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(linkedinMessage);
        }

        window.open($picked.val(), '_blank', 'noopener');
        $('#sendLinkedinModal').modal('hide');
        Swal.fire({
            icon: 'success',
            title: 'Message copied',
            html: 'On ' + $('<div>').text($picked.data('name') || 'their').html() + '\'s LinkedIn profile, click <b>Message</b> and paste (Ctrl+V / Cmd+V).',
            timer: 6000
        });
    });

    // Lead files: drag & drop, show the picked files before uploading.
    (function () {
        var input = document.getElementById('leadFilesInput');
        if (!input) return;
        var zone = document.getElementById('leadDropzone');
        var maxBytes = 25 * 1024 * 1024;
        var allowed = ['jpg','jpeg','png','gif','webp','bmp','heic','heif','tif','tiff','pdf','doc','docx','xls','xlsx','xlsm','csv','txt','rtf','ppt','pptx','odt','ods','odp','eml','msg','dwg','dxf','zip','rar','7z','mp3','wav','m4a','aac','ogg','amr','mp4','mov','avi','mkv','webm','3gp'];

        function sizeText(bytes) {
            return bytes >= 1048576 ? (bytes / 1048576).toFixed(1) + ' MB' : Math.max(1, Math.round(bytes / 1024)) + ' KB';
        }

        function render() {
            var files = Array.prototype.slice.call(input.files || []);
            var bad = 0;
            $('#leadFileQueue').html(files.map(function (file) {
                var ext = (file.name.split('.').pop() || '').toLowerCase();
                var problem = allowed.indexOf(ext) === -1 ? 'type not allowed' : (file.size > maxBytes ? 'larger than 25 MB' : '');
                if (problem) bad++;
                return '<li class="' + (problem ? 'is-bad' : '') + '"><span>' + $('<div>').text(file.name).html() + '</span><span>' + (problem || sizeText(file.size)) + '</span></li>';
            }).join(''));
            $('#leadFileActions').toggle(files.length > 0);
            $('#leadFilesUpload').prop('disabled', bad > 0 || files.length > 20);
            if (files.length > 20) $('#leadFileQueue').append('<li class="is-bad"><span>Max 20 files at once</span><span></span></li>');
        }

        input.addEventListener('change', render);
        ['dragenter', 'dragover'].forEach(function (name) {
            zone.addEventListener(name, function () { zone.classList.add('is-over'); });
        });
        ['dragleave', 'drop'].forEach(function (name) {
            zone.addEventListener(name, function () { zone.classList.remove('is-over'); });
        });
        $('#leadFilesClear').on('click', function () {
            input.value = '';
            render();
        });
        $('#frmLeadfileUpload').on('submit', function () {
            $('#leadFilesUpload').prop('disabled', true).html('Uploading...');
        });
    })();

    // View a lead file inside the page (image / PDF / video / audio / text).
    $(document).on('click', '.lead-file-preview', function () {
        var $el = $(this);
        var url = $el.data('url'), kind = $el.data('kind'), name = $el.data('name');
        var $body = $('#filePreviewBody').empty();
        $('#filePreviewTitle').text(name);
        $('#filePreviewOpen').attr('href', url);
        $('#filePreviewDownload').attr('href', $el.data('download'));

        if (kind === 'image') {
            $('<img>', { src: url, alt: name }).appendTo($body);
        } else if (kind === 'pdf' || kind === 'text') {
            $('<iframe>', { src: url, title: name }).appendTo($body);
        } else if (kind === 'video') {
            $('<video>', { src: url, controls: true, autoplay: true }).appendTo($body);
        } else if (kind === 'audio') {
            $('<audio>', { src: url, controls: true, autoplay: true }).appendTo($body);
        } else {
            $body.html('<div class="fpv-none"><i class="material-icons">visibility_off</i>This file type cannot be shown in the browser.<br>'
                + '<a href="' + $('<div>').text($el.data('download')).html() + '"><i class="material-icons" style="font-size:18px;display:inline;margin:0;color:inherit">download</i>Download to open</a></div>');
        }
        openPageModal('#filePreviewModal');
    });

    // Stop video / audio when the preview closes.
    $(document).on('hidden.bs.modal', '#filePreviewModal', function () {
        $('#filePreviewBody').empty();
    });

</script>
</x-app-layout>
