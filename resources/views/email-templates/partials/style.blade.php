<style>
    :root {
        --base: #006161
    }

    * {
        box-sizing: border-box;
        margin: 0;
    }

    body {
        margin: 0;
        font-family: 'Roboto', sans-serif;
        font-size: 13px;
        line-height: 21px;
        color: #737883;
        background: #f7fbff;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
    }

    h1, h2, h3, h4, h5, h6 {
        color: #334257;
    }

    p {
        margin-top: 0;
        margin-bottom: 1rem;
    }

    .fw-bold {
        font-weight: bold;
    }

    .main-table {
        width: 550px;
        background-color: #FFFFFF;
        margin: 50px auto;
        padding: 40px;
    }

    .main-table-inner {
        background-color: #E9F6FF;
        padding: 10px;
    }

    img {
        max-width: 100%;
    }
    .mt-1 {
        margin-top: 5px;
    }
    .mt-2 {
        margin-top: 10px;
    }
    .mb-1 {
        margin-bottom: 5px;
    }

    .mb-2 {
        margin-bottom: 10px;
    }

    .mb-3 {
        margin-bottom: 15px;
    }

    .mb-4 {
        margin-bottom: 20px;
    }

    .mb-5 {
        margin-bottom: 25px;
    }

    .p-2 {
        padding: 10px;
    }

    .pt-2 {
        padding-top: 10px;
    }

    .pt-3 {
        padding-top: 15px;
    }

    hr {
        border-color: rgba(0, 170, 109, 0.3);
        margin: 16px 0
    }

    /* .border-top {
        border-top: 1px solid rgba(0, 170, 109, 0.3);
        padding: 15px 0 10px;
        display: block;
    }

    .d-block {
        display: block;
    }



    .privacy a {
        text-decoration: none;
        color: #334257;
        position: relative;
    }

    .privacy a span {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #334257;
        display: inline-block;
        margin: 0 7px;
    }



    .copyright {
        text-align: center;
        display: block;
    } */

    .btn {
        display: inline-flex;
        padding: 10px 25px;
        align-items: center;
        gap: 10px;
        border: none;
        cursor: pointer;
        transition: all 300ms ease-in-out;
    }
    .social {
        margin: 15px 0 8px;
        display: block;
    }
    .privacy {
        text-align: center;
        display: block;
    }
    .btn-primary {
        color: #fff !important;
        background-color: #1455AC;
        border-color: #1455AC;
    }

    .btn-primary:hover {
        color: #fff;
        background-color: #0b5ed7;
        border-color: #0a58ca;
    }

    .btn-primary:focus {
        color: #fff;
        background-color: #0b5ed7;
        border-color: #0a58ca;
        box-shadow: 0 0 0 0.25rem rgba(49, 132, 253, 0.5);
    }

    .btn-primary:active {
        color: #fff;
        background-color: #0a58ca;
        border-color: #0a53be;
        box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
    }

    .btn-primary:disabled {
        color: #fff;
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    a {
        text-decoration: none;
    }

    /* .text-base {
        color: var(--base);
        font-weight: 700
    } */

    /* .mail-img-1 {
        width: 100%;
        height: 136px;
        object-fit: contain
    }

    .mail-img-2 {
        width: 100%;
        height: 45px;
        object-fit: contain
    }

    .mail-img-3 {
        width: 100%;
        height: 172px;
        object-fit: cover
    }

    .social img {
        width: 24px;
    } */

    .text-left {
        text-align: left;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .bg-white {
        background-color: #fff !important;
    }

    .d-flex {
        display: flex;
    }

    .justify-content-center {
        justify-content: center;
    }

    .justify-content-end {
        justify-content: flex-end;
    }

    /*.flex-1 {*/
    /*    flex-basis: 50%;*/
    /*}*/

    .gap-2 {
        gap: 10px;
    }

    .gap-3 {
        gap: 16px;
    }

    .gap-4 {
        gap: 24px;
    }

    .email-table {
        border-collapse: collapse;
        width: 100%;
    }

    .email-table thead {
        background-color: #F8F9FB;
    }

    .email-table th, .email-table td {
        padding: 10px 15px;
        color: #334257;
    }

    .email-dl {
        max-width: 240px;
        margin-left: auto;
    }

    .email-dl dt, .email-dl dd {
        margin-bottom: 5px;
        font-weight: 400;
        width: 47%;
        display: inline-block;
    }

    .text-success {
        color: #00AA6D !important;
    }

    .text-dark {
        color: #334257 !important;
    }

    .email-list-inline {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        list-style: none;
        padding: 0;
    }

    .email-list-inline li {
        position: relative;
    }

    .email-list-inline li:not(:last-child)::after {
        content: "";
        position: absolute;
        right: -14px;
        top: 50%;
        transform: translateY(-50%);
        display: inline-block;
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: #334257;
    }
    .m-auto{
        margin: auto;
    }
    .w-49{
        width: 49%;
    }
    .display-inline-block{
        display: inline-block;
    }
    .d-none{
        display: none;
    }
    .product-image {
        margin-right: 10px;
        width: 35px;
        height: 35px;
        border: 1px solid #e5e5e5;
        objectFit: cover
    }
    .bg-color-white-smoke{
        background-color: #F6F6F6;
    }
    .social-media-icon{
        display: flex;
        justify-content: center;
        gap: 24px;
        align-items: center;
        margin-bottom: 16px;
        margin-top: 16px;
        font-size: 16px;
    }
    .social-media-icon a{
        margin-right: 10px;
    }
    .social-media-icon a:last-child{
        margin-right: 0;
    }
    .mx-auto{
        margin-left: auto;
        margin-right: auto;

    }
    .text-nowrap{
        white-space: nowrap;
    }
</style>
