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

<div class="modal fade" id="callFeedbackModal" tabindex="-1" role="dialog" aria-labelledby="callFeedbackTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content call-feedback-modal">
            <form id="callFeedbackForm">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="callFeedbackTitle">Call remark</h5>
                        <small id="callFeedbackSub"></small>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="callFeedbackUrl">
                    <div class="form-group">
                        <label for="callFeedbackCallStatus">Call status @if($call_statuses->isNotEmpty())<span style="color:red">*</span>@endif</label>
                        @if($call_statuses->isNotEmpty())
                        <select class="form-control" id="callFeedbackCallStatus" name="call_status">
                            <option value="">Select what happened on the call</option>
                            @foreach($call_statuses as $callStatus)
                            <option value="{{ $callStatus->id }}">{{ $callStatus->display_name ?: $callStatus->status_name }}</option>
                            @endforeach
                        </select>
                        @else
                        <input type="hidden" id="callFeedbackCallStatus" value="">
                        <small class="call-feedback-hint d-block">No call statuses yet. Add them in Status master with module "Lead Call Feedback".</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="callFeedbackStatus">Lead type</label>
                        <select class="form-control" id="callFeedbackStatus" name="status">
                            <option value="0" {{ (int) $lead->status === 0 ? 'selected' : '' }}>Pending</option>
                            @foreach($status as $stat)
                            <option value="{{ $stat['id'] }}" {{ (int) $lead->status === (int) $stat['id'] ? 'selected' : '' }}>{{ $stat['display_name'] }}</option>
                            @endforeach
                        </select>
                        <small class="call-feedback-hint">Keep the current lead type or change it.</small>
                    </div>
                    <div class="form-group">
                        <label for="callFeedbackRemark">Remark <span style="color:red">*</span></label>
                        <textarea class="form-control" id="callFeedbackRemark" name="remark" rows="4" maxlength="1000" placeholder="What was discussed on the call?" required></textarea>
                    </div>
                    <p class="call-feedback-error" id="callFeedbackError"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Later</button>
                    <button type="submit" class="btn btn-primary" id="callFeedbackSave">Save remark</button>
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
        var $btn = $(this);
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
    });

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
        $('select#callFeedbackCallStatus').val('');
        $('#callFeedbackError').text('');
        $('#callFeedbackModal').modal('show');
        setTimeout(function () { $('#callFeedbackRemark').trigger('focus'); }, 400);
    }

    $(document).on('click', '.call-remark-btn', function () {
        openCallFeedback($(this).data('feedback-url'), $(this).data('call-label'));
    });

    $('#callFeedbackForm').on('submit', function (event) {
        event.preventDefault();
        var remark = $.trim($('#callFeedbackRemark').val());
        if ($('select#callFeedbackCallStatus').length && !$('#callFeedbackCallStatus').val()) {
            $('#callFeedbackError').text('Please select the call status.');
            return;
        }
        if (!remark) {
            $('#callFeedbackError').text('Please enter a remark.');
            return;
        }
        var $save = $('#callFeedbackSave').prop('disabled', true).text('Saving...');
        $.ajax({
            url: $('#callFeedbackUrl').val(),
            method: 'POST',
            data: { _token: '{{ csrf_token() }}', call_status: $('#callFeedbackCallStatus').val(), status: $('#callFeedbackStatus').val(), remark: remark },
            success: function () {
                $('#callFeedbackModal').modal('hide');
                window.location.reload();
            },
            error: function (xhr) {
                var res = xhr.responseJSON || {};
                var message = res.message || 'Could not save the remark.';
                if (res.errors) message = Object.values(res.errors)[0][0];
                $('#callFeedbackError').text(message);
            },
            complete: function () { $save.prop('disabled', false).text('Save remark'); }
        });
    });

    // Email / LinkedIn drafts: new, edit, cancel, copy.
    $(document).on('click', '.draft-new-btn', function () {
        var $form = $($(this).data('draft-form'));
        $form.find('.draft-id').val('');
        $form.find('.draft-text').val('');
        $form.slideDown(150);
        $form.find('.draft-text').trigger('focus');
    });

    $(document).on('click', '.draft-edit', function () {
        var $form = $($(this).data('draft-form'));
        $form.find('.draft-id').val($(this).data('id'));
        $form.find('.draft-text').val($(this).data('text'));
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

</script>
</x-app-layout>
