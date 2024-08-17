-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 16 août 2024 à 22:09
-- Version du serveur : 10.11.8-MariaDB-cll-lve
-- Version de PHP : 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `u993749535_nichen_v1_0`
--

-- --------------------------------------------------------

--
-- Structure de la table `addon_settings`
--

CREATE TABLE `addon_settings` (
  `id` char(36) NOT NULL,
  `key_name` varchar(191) DEFAULT NULL,
  `live_values` longtext DEFAULT NULL,
  `test_values` longtext DEFAULT NULL,
  `settings_type` varchar(255) DEFAULT NULL,
  `mode` varchar(20) NOT NULL DEFAULT 'live',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `additional_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `addon_settings`
--

INSERT INTO `addon_settings` (`id`, `key_name`, `live_values`, `test_values`, `settings_type`, `mode`, `is_active`, `created_at`, `updated_at`, `additional_data`) VALUES
('070c6bbd-d777-11ed-96f4-0c7a158e4469', 'twilio', '{\"gateway\":\"twilio\",\"mode\":\"live\",\"status\":\"0\",\"sid\":\"data\",\"messaging_service_sid\":\"data\",\"token\":\"data\",\"from\":\"data\",\"otp_template\":\"data\"}', '{\"gateway\":\"twilio\",\"mode\":\"live\",\"status\":\"0\",\"sid\":\"data\",\"messaging_service_sid\":\"data\",\"token\":\"data\",\"from\":\"data\",\"otp_template\":\"data\"}', 'sms_config', 'live', 0, NULL, '2023-08-12 07:01:29', NULL),
('070c766c-d777-11ed-96f4-0c7a158e4469', '2factor', '{\"gateway\":\"2factor\",\"mode\":\"live\",\"status\":\"0\",\"api_key\":\"data\"}', '{\"gateway\":\"2factor\",\"mode\":\"live\",\"status\":\"0\",\"api_key\":\"data\"}', 'sms_config', 'live', 0, NULL, '2023-08-12 07:01:36', NULL),
('0d8a9308-d6a5-11ed-962c-0c7a158e4469', 'mercadopago', '{\"gateway\":\"mercadopago\",\"mode\":\"live\",\"status\":0,\"access_token\":\"\",\"public_key\":\"\"}', '{\"gateway\":\"mercadopago\",\"mode\":\"live\",\"status\":0,\"access_token\":\"\",\"public_key\":\"\"}', 'payment_config', 'test', 0, NULL, '2023-08-27 11:57:11', '{\"gateway_title\":\"Mercadopago\",\"gateway_image\":null}'),
('0d8a9e49-d6a5-11ed-962c-0c7a158e4469', 'liqpay', '{\"gateway\":\"liqpay\",\"mode\":\"live\",\"status\":0,\"private_key\":\"\",\"public_key\":\"\"}', '{\"gateway\":\"liqpay\",\"mode\":\"live\",\"status\":0,\"private_key\":\"\",\"public_key\":\"\"}', 'payment_config', 'test', 0, NULL, '2023-08-12 06:32:31', '{\"gateway_title\":\"Liqpay\",\"gateway_image\":null}'),
('101befdf-d44b-11ed-8564-0c7a158e4469', 'paypal', '{\"gateway\":\"paypal\",\"mode\":\"live\",\"status\":\"0\",\"client_id\":\"\",\"client_secret\":\"\"}', '{\"gateway\":\"paypal\",\"mode\":\"live\",\"status\":\"0\",\"client_id\":\"\",\"client_secret\":\"\"}', 'payment_config', 'test', 0, NULL, '2023-08-30 03:41:32', '{\"gateway_title\":\"Paypal\",\"gateway_image\":null}'),
('133d9647-cabb-11ed-8fec-0c7a158e4469', 'hyper_pay', '{\"gateway\":\"hyper_pay\",\"mode\":\"test\",\"status\":\"0\",\"entity_id\":\"data\",\"access_code\":\"data\"}', '{\"gateway\":\"hyper_pay\",\"mode\":\"test\",\"status\":\"0\",\"entity_id\":\"data\",\"access_code\":\"data\"}', 'payment_config', 'test', 0, NULL, '2023-08-12 06:32:42', '{\"gateway_title\":null,\"gateway_image\":\"\"}'),
('1821029f-d776-11ed-96f4-0c7a158e4469', 'msg91', '{\"gateway\":\"msg91\",\"mode\":\"live\",\"status\":\"0\",\"template_id\":\"data\",\"auth_key\":\"data\"}', '{\"gateway\":\"msg91\",\"mode\":\"live\",\"status\":\"0\",\"template_id\":\"data\",\"auth_key\":\"data\"}', 'sms_config', 'live', 0, NULL, '2023-08-12 07:01:48', NULL),
('18210f2b-d776-11ed-96f4-0c7a158e4469', 'nexmo', '{\"gateway\":\"nexmo\",\"mode\":\"live\",\"status\":\"0\",\"api_key\":\"\",\"api_secret\":\"\",\"token\":\"\",\"from\":\"\",\"otp_template\":\"\"}', '{\"gateway\":\"nexmo\",\"mode\":\"live\",\"status\":\"0\",\"api_key\":\"\",\"api_secret\":\"\",\"token\":\"\",\"from\":\"\",\"otp_template\":\"\"}', 'sms_config', 'live', 0, NULL, '2023-04-10 02:14:44', NULL),
('18fbb21f-d6ad-11ed-962c-0c7a158e4469', 'foloosi', '{\"gateway\":\"foloosi\",\"mode\":\"test\",\"status\":\"0\",\"merchant_key\":\"data\"}', '{\"gateway\":\"foloosi\",\"mode\":\"test\",\"status\":\"0\",\"merchant_key\":\"data\"}', 'payment_config', 'test', 0, NULL, '2023-08-12 06:34:33', '{\"gateway_title\":null,\"gateway_image\":\"\"}'),
('2767d142-d6a1-11ed-962c-0c7a158e4469', 'paytm', '{\"gateway\":\"paytm\",\"mode\":\"live\",\"status\":0,\"merchant_key\":\"\",\"merchant_id\":\"\",\"merchant_website_link\":\"\"}', '{\"gateway\":\"paytm\",\"mode\":\"live\",\"status\":0,\"merchant_key\":\"\",\"merchant_id\":\"\",\"merchant_website_link\":\"\"}', 'payment_config', 'test', 0, NULL, '2023-08-22 06:30:55', '{\"gateway_title\":\"Paytm\",\"gateway_image\":null}'),
('3201d2e6-c937-11ed-a424-0c7a158e4469', 'amazon_pay', '{\"gateway\":\"amazon_pay\",\"mode\":\"test\",\"status\":\"0\",\"pass_phrase\":\"data\",\"access_code\":\"data\",\"merchant_identifier\":\"data\"}', '{\"gateway\":\"amazon_pay\",\"mode\":\"test\",\"status\":\"0\",\"pass_phrase\":\"data\",\"access_code\":\"data\",\"merchant_identifier\":\"data\"}', 'payment_config', 'test', 0, NULL, '2023-08-12 06:36:07', '{\"gateway_title\":null,\"gateway_image\":\"\"}'),
('4593b25c-d6a1-11ed-962c-0c7a158e4469', 'paytabs', '{\"gateway\":\"paytabs\",\"mode\":\"live\",\"status\":0,\"profile_id\":\"\",\"server_key\":\"\",\"base_url\":\"https:\\/\\/secure-egypt.paytabs.com\\/\"}', '{\"gateway\":\"paytabs\",\"mode\":\"live\",\"status\":0,\"profile_id\":\"\",\"server_key\":\"\",\"base_url\":\"https:\\/\\/secure-egypt.paytabs.com\\/\"}', 'payment_config', 'test', 0, NULL, '2023-08-12 06:34:51', '{\"gateway_title\":\"Paytabs\",\"gateway_image\":null}'),
('4e9b8dfb-e7d1-11ed-a559-0c7a158e4469', 'bkash', '{\"gateway\":\"bkash\",\"mode\":\"live\",\"status\":\"0\",\"app_key\":\"\",\"app_secret\":\"\",\"username\":\"\",\"password\":\"\"}', '{\"gateway\":\"bkash\",\"mode\":\"live\",\"status\":\"0\",\"app_key\":\"\",\"app_secret\":\"\",\"username\":\"\",\"password\":\"\"}', 'payment_config', 'test', 0, NULL, '2023-08-12 06:39:42', '{\"gateway_title\":\"Bkash\",\"gateway_image\":null}'),
('544a24a4-c872-11ed-ac7a-0c7a158e4469', 'fatoorah', '{\"gateway\":\"fatoorah\",\"mode\":\"test\",\"status\":\"0\",\"api_key\":\"data\"}', '{\"gateway\":\"fatoorah\",\"mode\":\"test\",\"status\":\"0\",\"api_key\":\"data\"}', 'payment_config', 'test', 0, NULL, '2023-08-12 06:36:24', '{\"gateway_title\":null,\"gateway_image\":\"\"}'),
('58c1bc8a-d6ac-11ed-962c-0c7a158e4469', 'ccavenue', '{\"gateway\":\"ccavenue\",\"mode\":\"test\",\"status\":\"0\",\"merchant_id\":\"data\",\"working_key\":\"data\",\"access_code\":\"data\"}', '{\"gateway\":\"ccavenue\",\"mode\":\"test\",\"status\":\"0\",\"merchant_id\":\"data\",\"working_key\":\"data\",\"access_code\":\"data\"}', 'payment_config', 'test', 0, NULL, '2023-08-30 03:42:38', '{\"gateway_title\":null,\"gateway_image\":\"2023-04-13-643783f01d386.png\"}'),
('5e2d2ef9-d6ab-11ed-962c-0c7a158e4469', 'thawani', '{\"gateway\":\"thawani\",\"mode\":\"test\",\"status\":\"0\",\"public_key\":\"data\",\"private_key\":\"data\"}', '{\"gateway\":\"thawani\",\"mode\":\"test\",\"status\":\"0\",\"public_key\":\"data\",\"private_key\":\"data\"}', 'payment_config', 'test', 0, NULL, '2023-08-30 04:50:40', '{\"gateway_title\":null,\"gateway_image\":\"2023-04-13-64378f9856f29.png\"}'),
('60cc83cc-d5b9-11ed-b56f-0c7a158e4469', 'sixcash', '{\"gateway\":\"sixcash\",\"mode\":\"test\",\"status\":\"0\",\"public_key\":\"data\",\"secret_key\":\"data\",\"merchant_number\":\"data\",\"base_url\":\"data\"}', '{\"gateway\":\"sixcash\",\"mode\":\"test\",\"status\":\"0\",\"public_key\":\"data\",\"secret_key\":\"data\",\"merchant_number\":\"data\",\"base_url\":\"data\"}', 'payment_config', 'test', 0, NULL, '2023-08-30 04:16:17', '{\"gateway_title\":null,\"gateway_image\":\"2023-04-12-6436774e77ff9.png\"}'),
('68579846-d8e8-11ed-8249-0c7a158e4469', 'alphanet_sms', '{\"gateway\":\"alphanet_sms\",\"mode\":\"live\",\"status\":0,\"api_key\":\"\",\"otp_template\":\"\"}', '{\"gateway\":\"alphanet_sms\",\"mode\":\"live\",\"status\":0,\"api_key\":\"\",\"otp_template\":\"\"}', 'sms_config', 'live', 0, NULL, NULL, NULL),
('6857a2e8-d8e8-11ed-8249-0c7a158e4469', 'sms_to', '{\"gateway\":\"sms_to\",\"mode\":\"live\",\"status\":0,\"api_key\":\"\",\"sender_id\":\"\",\"otp_template\":\"\"}', '{\"gateway\":\"sms_to\",\"mode\":\"live\",\"status\":0,\"api_key\":\"\",\"sender_id\":\"\",\"otp_template\":\"\"}', 'sms_config', 'live', 0, NULL, NULL, NULL),
('74c30c00-d6a6-11ed-962c-0c7a158e4469', 'hubtel', '{\"gateway\":\"hubtel\",\"mode\":\"test\",\"status\":\"0\",\"account_number\":\"data\",\"api_id\":\"data\",\"api_key\":\"data\"}', '{\"gateway\":\"hubtel\",\"mode\":\"test\",\"status\":\"0\",\"account_number\":\"data\",\"api_id\":\"data\",\"api_key\":\"data\"}', 'payment_config', 'test', 0, NULL, '2023-08-12 06:37:43', '{\"gateway_title\":null,\"gateway_image\":\"\"}'),
('74e46b0a-d6aa-11ed-962c-0c7a158e4469', 'tap', '{\"gateway\":\"tap\",\"mode\":\"test\",\"status\":\"0\",\"secret_key\":\"data\"}', '{\"gateway\":\"tap\",\"mode\":\"test\",\"status\":\"0\",\"secret_key\":\"data\"}', 'payment_config', 'test', 0, NULL, '2023-08-30 04:50:09', '{\"gateway_title\":null,\"gateway_image\":\"\"}'),
('761ca96c-d1eb-11ed-87ca-0c7a158e4469', 'swish', '{\"gateway\":\"swish\",\"mode\":\"test\",\"status\":\"0\",\"number\":\"data\"}', '{\"gateway\":\"swish\",\"mode\":\"test\",\"status\":\"0\",\"number\":\"data\"}', 'payment_config', 'test', 0, NULL, '2023-08-30 04:17:02', '{\"gateway_title\":null,\"gateway_image\":\"\"}'),
('7b1c3c5f-d2bd-11ed-b485-0c7a158e4469', 'payfast', '{\"gateway\":\"payfast\",\"mode\":\"test\",\"status\":\"0\",\"merchant_id\":\"data\",\"secured_key\":\"data\"}', '{\"gateway\":\"payfast\",\"mode\":\"test\",\"status\":\"0\",\"merchant_id\":\"data\",\"secured_key\":\"data\"}', 'payment_config', 'test', 0, NULL, '2023-08-30 04:18:13', '{\"gateway_title\":null,\"gateway_image\":\"\"}'),
('8592417b-d1d1-11ed-a984-0c7a158e4469', 'esewa', '{\"gateway\":\"esewa\",\"mode\":\"test\",\"status\":\"0\",\"merchantCode\":\"data\"}', '{\"gateway\":\"esewa\",\"mode\":\"test\",\"status\":\"0\",\"merchantCode\":\"data\"}', 'payment_config', 'test', 0, NULL, '2023-08-30 04:17:38', '{\"gateway_title\":null,\"gateway_image\":\"\"}'),
('9162a1dc-cdf1-11ed-affe-0c7a158e4469', 'viva_wallet', '{\"gateway\":\"viva_wallet\",\"mode\":\"test\",\"status\":\"0\",\"client_id\": \"\",\"client_secret\": \"\", \"source_code\":\"\"}\n', '{\"gateway\":\"viva_wallet\",\"mode\":\"test\",\"status\":\"0\",\"client_id\": \"\",\"client_secret\": \"\", \"source_code\":\"\"}\n', 'payment_config', 'test', 0, NULL, NULL, NULL),
('998ccc62-d6a0-11ed-962c-0c7a158e4469', 'stripe', '{\"gateway\":\"stripe\",\"mode\":\"live\",\"status\":\"0\",\"api_key\":null,\"published_key\":null}', '{\"gateway\":\"stripe\",\"mode\":\"live\",\"status\":\"0\",\"api_key\":null,\"published_key\":null}', 'payment_config', 'test', 0, NULL, '2023-08-30 04:18:55', '{\"gateway_title\":\"Stripe\",\"gateway_image\":null}'),
('a3313755-c95d-11ed-b1db-0c7a158e4469', 'iyzi_pay', '{\"gateway\":\"iyzi_pay\",\"mode\":\"test\",\"status\":\"0\",\"api_key\":\"data\",\"secret_key\":\"data\",\"base_url\":\"data\"}', '{\"gateway\":\"iyzi_pay\",\"mode\":\"test\",\"status\":\"0\",\"api_key\":\"data\",\"secret_key\":\"data\",\"base_url\":\"data\"}', 'payment_config', 'test', 0, NULL, '2023-08-30 04:20:02', '{\"gateway_title\":null,\"gateway_image\":\"\"}'),
('a76c8993-d299-11ed-b485-0c7a158e4469', 'momo', '{\"gateway\":\"momo\",\"mode\":\"live\",\"status\":\"0\",\"api_key\":\"data\",\"api_user\":\"data\",\"subscription_key\":\"data\"}', '{\"gateway\":\"momo\",\"mode\":\"live\",\"status\":\"0\",\"api_key\":\"data\",\"api_user\":\"data\",\"subscription_key\":\"data\"}', 'payment_config', 'live', 0, NULL, '2023-08-30 04:19:28', '{\"gateway_title\":null,\"gateway_image\":\"\"}'),
('a8608119-cc76-11ed-9bca-0c7a158e4469', 'moncash', '{\"gateway\":\"moncash\",\"mode\":\"test\",\"status\":\"0\",\"client_id\":\"data\",\"secret_key\":\"data\"}', '{\"gateway\":\"moncash\",\"mode\":\"test\",\"status\":\"0\",\"client_id\":\"data\",\"secret_key\":\"data\"}', 'payment_config', 'test', 0, NULL, '2023-08-30 04:47:34', '{\"gateway_title\":null,\"gateway_image\":\"\"}'),
('ad5af1c1-d6a2-11ed-962c-0c7a158e4469', 'razor_pay', '{\"gateway\":\"razor_pay\",\"mode\":\"live\",\"status\":\"0\",\"api_key\":null,\"api_secret\":null}', '{\"gateway\":\"razor_pay\",\"mode\":\"live\",\"status\":\"0\",\"api_key\":null,\"api_secret\":null}', 'payment_config', 'test', 0, NULL, '2023-08-30 04:47:00', '{\"gateway_title\":\"Razor pay\",\"gateway_image\":null}'),
('ad5b02a0-d6a2-11ed-962c-0c7a158e4469', 'senang_pay', '{\"gateway\":\"senang_pay\",\"mode\":\"live\",\"status\":\"0\",\"callback_url\":null,\"secret_key\":null,\"merchant_id\":null}', '{\"gateway\":\"senang_pay\",\"mode\":\"live\",\"status\":\"0\",\"callback_url\":null,\"secret_key\":null,\"merchant_id\":null}', 'payment_config', 'test', 0, NULL, '2023-08-27 09:58:57', '{\"gateway_title\":\"Senang pay\",\"gateway_image\":null}'),
('b6c333f6-d8e9-11ed-8249-0c7a158e4469', 'akandit_sms', '{\"gateway\":\"akandit_sms\",\"mode\":\"live\",\"status\":0,\"username\":\"\",\"password\":\"\",\"otp_template\":\"\"}', '{\"gateway\":\"akandit_sms\",\"mode\":\"live\",\"status\":0,\"username\":\"\",\"password\":\"\",\"otp_template\":\"\"}', 'sms_config', 'live', 0, NULL, NULL, NULL),
('b6c33c87-d8e9-11ed-8249-0c7a158e4469', 'global_sms', '{\"gateway\":\"global_sms\",\"mode\":\"live\",\"status\":0,\"user_name\":\"\",\"password\":\"\",\"from\":\"\",\"otp_template\":\"\"}', '{\"gateway\":\"global_sms\",\"mode\":\"live\",\"status\":0,\"user_name\":\"\",\"password\":\"\",\"from\":\"\",\"otp_template\":\"\"}', 'sms_config', 'live', 0, NULL, NULL, NULL),
('b8992bd4-d6a0-11ed-962c-0c7a158e4469', 'paymob_accept', '{\"gateway\":\"paymob_accept\",\"mode\":\"live\",\"status\":\"0\",\"callback_url\":null,\"api_key\":\"\",\"iframe_id\":\"\",\"integration_id\":\"\",\"hmac\":\"\"}', '{\"gateway\":\"paymob_accept\",\"mode\":\"live\",\"status\":\"0\",\"callback_url\":null,\"api_key\":\"\",\"iframe_id\":\"\",\"integration_id\":\"\",\"hmac\":\"\"}', 'payment_config', 'test', 0, NULL, NULL, '{\"gateway_title\":\"Paymob accept\",\"gateway_image\":null}'),
('c41c0dcd-d119-11ed-9f67-0c7a158e4469', 'maxicash', '{\"gateway\":\"maxicash\",\"mode\":\"test\",\"status\":\"0\",\"merchantId\":\"data\",\"merchantPassword\":\"data\"}', '{\"gateway\":\"maxicash\",\"mode\":\"test\",\"status\":\"0\",\"merchantId\":\"data\",\"merchantPassword\":\"data\"}', 'payment_config', 'test', 0, NULL, '2023-08-30 04:49:15', '{\"gateway_title\":null,\"gateway_image\":\"\"}'),
('c9249d17-cd60-11ed-b879-0c7a158e4469', 'pvit', '{\"gateway\":\"pvit\",\"mode\":\"test\",\"status\":\"0\",\"mc_tel_merchant\": \"\",\"access_token\": \"\", \"mc_merchant_code\": \"\"}', '{\"gateway\":\"pvit\",\"mode\":\"test\",\"status\":\"0\",\"mc_tel_merchant\": \"\",\"access_token\": \"\", \"mc_merchant_code\": \"\"}', 'payment_config', 'test', 0, NULL, NULL, NULL),
('cb0081ce-d775-11ed-96f4-0c7a158e4469', 'releans', '{\"gateway\":\"releans\",\"mode\":\"live\",\"status\":0,\"api_key\":\"\",\"from\":\"\",\"otp_template\":\"\"}', '{\"gateway\":\"releans\",\"mode\":\"live\",\"status\":0,\"api_key\":\"\",\"from\":\"\",\"otp_template\":\"\"}', 'sms_config', 'live', 0, NULL, '2023-04-10 02:14:44', NULL),
('d4f3f5f1-d6a0-11ed-962c-0c7a158e4469', 'flutterwave', '{\"gateway\":\"flutterwave\",\"mode\":\"live\",\"status\":0,\"secret_key\":\"\",\"public_key\":\"\",\"hash\":\"\"}', '{\"gateway\":\"flutterwave\",\"mode\":\"live\",\"status\":0,\"secret_key\":\"\",\"public_key\":\"\",\"hash\":\"\"}', 'payment_config', 'test', 0, NULL, '2023-08-30 04:41:03', '{\"gateway_title\":\"Flutterwave\",\"gateway_image\":null}'),
('d822f1a5-c864-11ed-ac7a-0c7a158e4469', 'paystack', '{\"gateway\":\"paystack\",\"mode\":\"live\",\"status\":\"0\",\"callback_url\":\"https:\\/\\/api.paystack.co\",\"public_key\":null,\"secret_key\":null,\"merchant_email\":null}', '{\"gateway\":\"paystack\",\"mode\":\"live\",\"status\":\"0\",\"callback_url\":\"https:\\/\\/api.paystack.co\",\"public_key\":null,\"secret_key\":null,\"merchant_email\":null}', 'payment_config', 'test', 0, NULL, '2023-08-30 04:20:45', '{\"gateway_title\":\"Paystack\",\"gateway_image\":null}'),
('daec8d59-c893-11ed-ac7a-0c7a158e4469', 'xendit', '{\"gateway\":\"xendit\",\"mode\":\"test\",\"status\":\"0\",\"api_key\":\"data\"}', '{\"gateway\":\"xendit\",\"mode\":\"test\",\"status\":\"0\",\"api_key\":\"data\"}', 'payment_config', 'test', 0, NULL, '2023-08-12 06:35:46', '{\"gateway_title\":null,\"gateway_image\":\"\"}'),
('dc0f5fc9-d6a5-11ed-962c-0c7a158e4469', 'worldpay', '{\"gateway\":\"worldpay\",\"mode\":\"test\",\"status\":\"0\",\"OrgUnitId\":\"data\",\"jwt_issuer\":\"data\",\"mac\":\"data\",\"merchantCode\":\"data\",\"xml_password\":\"data\"}', '{\"gateway\":\"worldpay\",\"mode\":\"test\",\"status\":\"0\",\"OrgUnitId\":\"data\",\"jwt_issuer\":\"data\",\"mac\":\"data\",\"merchantCode\":\"data\",\"xml_password\":\"data\"}', 'payment_config', 'test', 0, NULL, '2023-08-12 06:35:26', '{\"gateway_title\":null,\"gateway_image\":\"\"}'),
('e0450278-d8eb-11ed-8249-0c7a158e4469', 'signal_wire', '{\"gateway\":\"signal_wire\",\"mode\":\"live\",\"status\":0,\"project_id\":\"\",\"token\":\"\",\"space_url\":\"\",\"from\":\"\",\"otp_template\":\"\"}', '{\"gateway\":\"signal_wire\",\"mode\":\"live\",\"status\":0,\"project_id\":\"\",\"token\":\"\",\"space_url\":\"\",\"from\":\"\",\"otp_template\":\"\"}', 'sms_config', 'live', 0, NULL, NULL, NULL),
('e0450b40-d8eb-11ed-8249-0c7a158e4469', 'paradox', '{\"gateway\":\"paradox\",\"mode\":\"live\",\"status\":\"0\",\"api_key\":\"\",\"sender_id\":\"\"}', '{\"gateway\":\"paradox\",\"mode\":\"live\",\"status\":\"0\",\"api_key\":\"\",\"sender_id\":\"\"}', 'sms_config', 'live', 0, NULL, '2023-09-10 01:14:01', NULL),
('ea346efe-cdda-11ed-affe-0c7a158e4469', 'ssl_commerz', '{\"gateway\":\"ssl_commerz\",\"mode\":\"live\",\"status\":\"0\",\"store_id\":\"\",\"store_password\":\"\"}', '{\"gateway\":\"ssl_commerz\",\"mode\":\"live\",\"status\":\"0\",\"store_id\":\"\",\"store_password\":\"\"}', 'payment_config', 'test', 0, NULL, '2023-08-30 03:43:49', '{\"gateway_title\":\"Ssl commerz\",\"gateway_image\":null}'),
('eed88336-d8ec-11ed-8249-0c7a158e4469', 'hubtel', '{\"gateway\":\"hubtel\",\"mode\":\"live\",\"status\":0,\"sender_id\":\"\",\"client_id\":\"\",\"client_secret\":\"\",\"otp_template\":\"\"}', '{\"gateway\":\"hubtel\",\"mode\":\"live\",\"status\":0,\"sender_id\":\"\",\"client_id\":\"\",\"client_secret\":\"\",\"otp_template\":\"\"}', 'sms_config', 'live', 0, NULL, NULL, NULL),
('f149c546-d8ea-11ed-8249-0c7a158e4469', 'viatech', '{\"gateway\":\"viatech\",\"mode\":\"live\",\"status\":0,\"api_url\":\"\",\"api_key\":\"\",\"sender_id\":\"\",\"otp_template\":\"\"}', '{\"gateway\":\"viatech\",\"mode\":\"live\",\"status\":0,\"api_url\":\"\",\"api_key\":\"\",\"sender_id\":\"\",\"otp_template\":\"\"}', 'sms_config', 'live', 0, NULL, NULL, NULL),
('f149cd9c-d8ea-11ed-8249-0c7a158e4469', '019_sms', '{\"gateway\":\"019_sms\",\"mode\":\"live\",\"status\":0,\"password\":\"\",\"username\":\"\",\"username_for_token\":\"\",\"sender\":\"\",\"otp_template\":\"\"}', '{\"gateway\":\"019_sms\",\"mode\":\"live\",\"status\":0,\"password\":\"\",\"username\":\"\",\"username_for_token\":\"\",\"sender\":\"\",\"otp_template\":\"\"}', 'sms_config', 'live', 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `add_fund_bonus_categories`
--

CREATE TABLE `add_fund_bonus_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `bonus_type` varchar(50) NOT NULL,
  `bonus_amount` double(14,2) NOT NULL DEFAULT 0.00,
  `min_add_money_amount` double(14,2) NOT NULL DEFAULT 0.00,
  `max_bonus_amount` double(14,2) NOT NULL DEFAULT 0.00,
  `start_date_time` datetime DEFAULT NULL,
  `end_date_time` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(80) DEFAULT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `admin_role_id` bigint(20) NOT NULL DEFAULT 2,
  `image` varchar(30) NOT NULL DEFAULT 'def.png',
  `identify_image` text DEFAULT NULL,
  `identify_type` varchar(255) DEFAULT NULL,
  `identify_number` int(11) DEFAULT NULL,
  `email` varchar(80) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(80) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `admins`
--

INSERT INTO `admins` (`id`, `name`, `phone`, `admin_role_id`, `image`, `identify_image`, `identify_type`, `identify_number`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Mehdi H.', '0562380150', 1, 'def.png', NULL, NULL, NULL, 'mahdiharzallah21@gmail.com', NULL, '$2y$10$zRLabdnOiYybL12RYCsWmuGbyomz3kIJivY3boXkKi1qRHXD3EhZG', '7TzV2mfLGL7c4i5RcIYEM2GCRkGwsIBfHw6vOzPQSusEHYfdk38mlHJAO9HQ', '2024-08-08 03:41:30', '2024-08-08 03:41:30', 1);

-- --------------------------------------------------------

--
-- Structure de la table `admin_roles`
--

CREATE TABLE `admin_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `module_access` varchar(250) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `admin_roles`
--

INSERT INTO `admin_roles` (`id`, `name`, `module_access`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Master Admin', NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `admin_wallets`
--

CREATE TABLE `admin_wallets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) DEFAULT NULL,
  `inhouse_earning` double NOT NULL DEFAULT 0,
  `withdrawn` double NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `commission_earned` double(8,2) NOT NULL DEFAULT 0.00,
  `delivery_charge_earned` double(8,2) NOT NULL DEFAULT 0.00,
  `pending_amount` double(8,2) NOT NULL DEFAULT 0.00,
  `total_tax_collected` double(8,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `admin_wallets`
--

INSERT INTO `admin_wallets` (`id`, `admin_id`, `inhouse_earning`, `withdrawn`, `created_at`, `updated_at`, `commission_earned`, `delivery_charge_earned`, `pending_amount`, `total_tax_collected`) VALUES
(1, 1, 0, 0, NULL, NULL, 0.00, 0.00, 0.00, 0.00),
(2, 1, 0, 0, '2024-08-08 03:41:30', '2024-08-08 03:41:30', 0.00, 0.00, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Structure de la table `admin_wallet_histories`
--

CREATE TABLE `admin_wallet_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) DEFAULT NULL,
  `amount` double NOT NULL DEFAULT 0,
  `order_id` bigint(20) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `payment` varchar(191) NOT NULL DEFAULT 'received',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `attributes`
--

CREATE TABLE `attributes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `attributes`
--

INSERT INTO `attributes` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Sizes', '2024-08-15 15:53:23', '2024-08-15 15:53:23'),
(2, 'Custom vars', '2024-08-15 15:53:51', '2024-08-15 15:53:51');

-- --------------------------------------------------------

--
-- Structure de la table `banners`
--

CREATE TABLE `banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `banner_type` varchar(255) NOT NULL,
  `theme` varchar(255) NOT NULL DEFAULT 'default',
  `published` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `resource_type` varchar(191) DEFAULT NULL,
  `resource_id` bigint(20) DEFAULT NULL,
  `title` varchar(191) DEFAULT NULL,
  `sub_title` varchar(191) DEFAULT NULL,
  `button_text` varchar(191) DEFAULT NULL,
  `background_color` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `banners`
--

INSERT INTO `banners` (`id`, `photo`, `banner_type`, `theme`, `published`, `created_at`, `updated_at`, `url`, `resource_type`, `resource_id`, `title`, `sub_title`, `button_text`, `background_color`) VALUES
(1, '2024-08-15-66be09d7a03fc.webp', 'Main Banner', 'theme_aster', 1, '2024-08-15 13:59:51', '2024-08-15 13:59:56', 'https://store-front-nuxt.feeef.net/', 'shop', 1, NULL, NULL, NULL, NULL),
(2, '2024-08-15-66be20563f365.webp', 'Main Banner', 'default', 1, '2024-08-15 15:35:50', '2024-08-15 15:36:41', 'https://nichen.net/admin/banner/list', 'product', 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `billing_addresses`
--

CREATE TABLE `billing_addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `contact_person_name` varchar(191) DEFAULT NULL,
  `address_type` varchar(191) DEFAULT NULL,
  `address` varchar(191) DEFAULT NULL,
  `city` varchar(191) DEFAULT NULL,
  `zip` varchar(191) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `state` varchar(191) DEFAULT NULL,
  `country` varchar(191) DEFAULT NULL,
  `latitude` varchar(191) DEFAULT NULL,
  `longitude` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `image` varchar(50) NOT NULL DEFAULT 'def.png',
  `image_storage_type` varchar(10) DEFAULT 'public',
  `image_alt_text` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `brands`
--

INSERT INTO `brands` (`id`, `name`, `image`, `image_storage_type`, `image_alt_text`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Nike', '2024-08-08-66b3eb5a20ff6.webp', 'public', NULL, 1, '2024-08-08 03:47:06', '2024-08-08 03:47:06');

-- --------------------------------------------------------

--
-- Structure de la table `business_settings`
--

CREATE TABLE `business_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(50) NOT NULL,
  `value` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `business_settings`
--

INSERT INTO `business_settings` (`id`, `type`, `value`, `created_at`, `updated_at`) VALUES
(1, 'system_default_currency', '8', '2020-10-11 07:43:44', '2024-08-07 21:56:03'),
(2, 'language', '[{\"id\":\"1\",\"name\":\"english\",\"code\":\"en\",\"status\":1,\"default\":true}]', '2020-10-11 07:53:02', '2023-10-13 11:34:53'),
(3, 'mail_config', '{\"status\":0,\"name\":\"demo\",\"host\":\"mail.demo.com\",\"driver\":\"SMTP\",\"port\":\"587\",\"username\":\"info@demo.com\",\"email_id\":\"info@demo.com\",\"encryption\":\"TLS\",\"password\":\"demo\"}', '2020-10-12 10:29:18', '2021-07-06 12:32:01'),
(4, 'cash_on_delivery', '{\"status\":\"1\"}', NULL, '2021-05-25 21:21:15'),
(6, 'ssl_commerz_payment', '{\"status\":\"0\",\"environment\":\"sandbox\",\"store_id\":\"\",\"store_password\":\"\"}', '2020-11-09 08:36:51', '2023-01-10 05:51:56'),
(10, 'company_phone', '000000000', NULL, '2024-08-07 21:56:03'),
(11, 'company_name', 'Nichen', NULL, '2024-08-07 21:56:03'),
(12, 'company_web_logo', '{\"image_name\":\"2024-08-07-66b3ed73b7dc0.webp\",\"storage\":\"public\"}', NULL, '2024-08-07 21:56:03'),
(13, 'company_mobile_logo', '{\"image_name\":\"2024-08-07-66b3ed73e8f24.webp\",\"storage\":\"public\"}', NULL, '2024-08-07 21:56:03'),
(14, 'terms_condition', '<p>terms and conditions</p>', NULL, '2021-06-11 01:51:36'),
(15, 'about_us', '<p>this is about us page. hello and hi from about page description..</p>', NULL, '2021-06-11 01:42:53'),
(16, 'sms_nexmo', '{\"status\":\"0\",\"nexmo_key\":\"custo5cc042f7abf4c\",\"nexmo_secret\":\"custo5cc042f7abf4c@ssl\"}', NULL, NULL),
(17, 'company_email', 'contact@nichen.net', NULL, '2024-08-07 21:56:03'),
(18, 'colors', '{\"primary\":\"#ff8800\",\"secondary\":\"#000000\",\"primary_light\":\"#febc90\"}', '2020-10-11 13:53:02', '2024-08-07 21:56:03'),
(19, 'company_footer_logo', '{\"image_name\":\"2024-08-07-66b3ed744991f.webp\",\"storage\":\"public\"}', NULL, '2024-08-07 21:56:04'),
(20, 'company_copyright_text', 'Copy-rights Nichen@2024', NULL, '2024-08-07 21:56:03'),
(21, 'download_app_apple_stroe', '{\"status\":\"1\",\"link\":\"https:\\/\\/www.target.com\\/s\\/apple+store++now?ref=tgt_adv_XS000000&AFID=msn&fndsrc=tgtao&DFA=71700000012505188&CPNG=Electronics_Portable+Computers&adgroup=Portable+Computers&LID=700000001176246&LNM=apple+store+near+me+now&MT=b&network=s&device=c&location=12&targetid=kwd-81913773633608:loc-12&ds_rl=1246978&ds_rl=1248099&gclsrc=ds\"}', NULL, '2024-08-07 21:56:03'),
(22, 'download_app_google_stroe', '{\"status\":\"1\",\"link\":\"https:\\/\\/play.google.com\\/store?hl=en_US&gl=US\"}', NULL, '2024-08-07 21:56:03'),
(23, 'company_fav_icon', '{\"image_name\":\"2024-08-07-66b3ed747af55.webp\",\"storage\":\"public\"}', '2020-10-11 13:53:02', '2024-08-07 21:56:04'),
(24, 'fcm_topic', '', NULL, NULL),
(25, 'fcm_project_id', '', NULL, NULL),
(26, 'push_notification_key', 'Put your firebase server key here.', NULL, NULL),
(27, 'order_pending_message', '{\"status\":\"1\",\"message\":\"order pen message\"}', NULL, NULL),
(28, 'order_confirmation_msg', '{\"status\":\"1\",\"message\":\"Order con Message\"}', NULL, NULL),
(29, 'order_processing_message', '{\"status\":\"1\",\"message\":\"Order pro Message\"}', NULL, NULL),
(30, 'out_for_delivery_message', '{\"status\":\"1\",\"message\":\"Order ouut Message\"}', NULL, NULL),
(31, 'order_delivered_message', '{\"status\":\"1\",\"message\":\"Order del Message\"}', NULL, NULL),
(33, 'sales_commission', '0', NULL, '2021-06-11 18:13:13'),
(34, 'seller_registration', '1', NULL, '2021-06-04 21:02:48'),
(35, 'pnc_language', '[\"en\"]', NULL, NULL),
(36, 'order_returned_message', '{\"status\":\"1\",\"message\":\"Order hh Message\"}', NULL, NULL),
(37, 'order_failed_message', '{\"status\":null,\"message\":\"Order fa Message\"}', NULL, NULL),
(40, 'delivery_boy_assign_message', '{\"status\":0,\"message\":\"\"}', NULL, NULL),
(41, 'delivery_boy_start_message', '{\"status\":0,\"message\":\"\"}', NULL, NULL),
(42, 'delivery_boy_delivered_message', '{\"status\":0,\"message\":\"\"}', NULL, NULL),
(43, 'terms_and_conditions', '', NULL, NULL),
(44, 'minimum_order_value', '1', NULL, NULL),
(45, 'privacy_policy', '<p>my privacy policy</p>\r\n\r\n<p>&nbsp;</p>', NULL, '2021-07-06 11:09:07'),
(48, 'currency_model', 'single_currency', NULL, NULL),
(49, 'social_login', '[{\"login_medium\":\"google\",\"client_id\":\"\",\"client_secret\":\"\",\"status\":\"\"},{\"login_medium\":\"facebook\",\"client_id\":\"\",\"client_secret\":\"\",\"status\":\"\"}]', NULL, NULL),
(50, 'digital_payment', '{\"status\":\"1\"}', NULL, NULL),
(51, 'phone_verification', '', NULL, '2024-08-07 21:56:03'),
(52, 'email_verification', '', NULL, '2024-08-07 21:56:03'),
(53, 'order_verification', '0', NULL, NULL),
(54, 'country_code', 'DZ', NULL, '2024-08-07 21:56:03'),
(55, 'pagination_limit', '10', NULL, NULL),
(56, 'shipping_method', 'inhouse_shipping', NULL, NULL),
(59, 'forgot_password_verification', 'email', NULL, '2024-08-07 21:56:03'),
(61, 'stock_limit', '10', NULL, NULL),
(64, 'announcement', '{\"status\":null,\"color\":null,\"text_color\":null,\"announcement\":null}', NULL, NULL),
(65, 'fawry_pay', '{\"status\":0,\"merchant_code\":\"\",\"security_key\":\"\"}', NULL, '2022-01-18 09:46:30'),
(66, 'recaptcha', '{\"status\":0,\"site_key\":\"\",\"secret_key\":\"\"}', NULL, '2022-01-18 09:46:30'),
(67, 'seller_pos', '0', NULL, NULL),
(70, 'refund_day_limit', '0', NULL, NULL),
(71, 'business_mode', 'multi', NULL, '2024-08-07 21:56:03'),
(72, 'mail_config_sendgrid', '{\"status\":0,\"name\":\"\",\"host\":\"\",\"driver\":\"\",\"port\":\"\",\"username\":\"\",\"email_id\":\"\",\"encryption\":\"\",\"password\":\"\"}', NULL, NULL),
(73, 'decimal_point_settings', '2', NULL, '2024-08-07 21:56:03'),
(74, 'shop_address', 'Algeria', NULL, '2024-08-07 21:56:03'),
(75, 'billing_input_by_customer', '1', NULL, NULL),
(76, 'wallet_status', '0', NULL, NULL),
(77, 'loyalty_point_status', '0', NULL, NULL),
(78, 'wallet_add_refund', '0', NULL, NULL),
(79, 'loyalty_point_exchange_rate', '0', NULL, NULL),
(80, 'loyalty_point_item_purchase_point', '0', NULL, NULL),
(81, 'loyalty_point_minimum_point', '0', NULL, NULL),
(82, 'minimum_order_limit', '1', NULL, NULL),
(83, 'product_brand', '1', NULL, NULL),
(84, 'digital_product', '1', NULL, NULL),
(85, 'delivery_boy_expected_delivery_date_message', '{\"status\":0,\"message\":\"\"}', NULL, NULL),
(86, 'order_canceled', '{\"status\":0,\"message\":\"\"}', NULL, NULL),
(87, 'refund-policy', '{\"status\":1,\"content\":\"\"}', NULL, '2023-03-04 06:25:36'),
(88, 'return-policy', '{\"status\":1,\"content\":\"\"}', NULL, '2023-03-04 06:25:36'),
(89, 'cancellation-policy', '{\"status\":1,\"content\":\"\"}', NULL, '2023-03-04 06:25:36'),
(90, 'offline_payment', '{\"status\":0}', NULL, '2023-03-04 06:25:36'),
(91, 'temporary_close', '{\"status\":0}', NULL, '2023-03-04 06:25:36'),
(92, 'vacation_add', '{\"status\":0,\"vacation_start_date\":null,\"vacation_end_date\":null,\"vacation_note\":null}', NULL, '2023-03-04 06:25:36'),
(93, 'cookie_setting', '{\"status\":0,\"cookie_text\":null}', NULL, '2023-03-04 06:25:36'),
(94, 'maximum_otp_hit', '0', NULL, '2023-06-13 13:04:49'),
(95, 'otp_resend_time', '0', NULL, '2023-06-13 13:04:49'),
(96, 'temporary_block_time', '0', NULL, '2023-06-13 13:04:49'),
(97, 'maximum_login_hit', '0', NULL, '2023-06-13 13:04:49'),
(98, 'temporary_login_block_time', '0', NULL, '2023-06-13 13:04:49'),
(104, 'apple_login', '[{\"login_medium\":\"apple\",\"client_id\":\"\",\"client_secret\":\"\",\"status\":0,\"team_id\":\"\",\"key_id\":\"\",\"service_file\":\"\",\"redirect_url\":\"\"}]', NULL, '2023-10-13 05:34:53'),
(105, 'ref_earning_status', '0', NULL, '2024-08-08 03:41:30'),
(106, 'ref_earning_exchange_rate', '0', NULL, '2024-08-08 03:41:30'),
(107, 'guest_checkout', '0', NULL, '2023-10-13 11:34:53'),
(108, 'minimum_order_amount', '0', NULL, '2023-10-13 11:34:53'),
(109, 'minimum_order_amount_by_seller', '0', NULL, '2023-10-13 11:34:53'),
(110, 'minimum_order_amount_status', '0', NULL, '2023-10-13 11:34:53'),
(111, 'admin_login_url', 'admin', NULL, '2023-10-13 11:34:53'),
(112, 'employee_login_url', 'employee', NULL, '2023-10-13 11:34:53'),
(113, 'free_delivery_status', '0', NULL, '2023-10-13 11:34:53'),
(114, 'free_delivery_responsibility', 'admin', NULL, '2023-10-13 11:34:53'),
(115, 'free_delivery_over_amount', '0', NULL, '2023-10-13 11:34:53'),
(116, 'free_delivery_over_amount_seller', '0', NULL, '2023-10-13 11:34:53'),
(117, 'add_funds_to_wallet', '0', NULL, '2023-10-13 11:34:53'),
(118, 'minimum_add_fund_amount', '0', NULL, '2023-10-13 11:34:53'),
(119, 'maximum_add_fund_amount', '0', NULL, '2023-10-13 11:34:53'),
(120, 'user_app_version_control', '{\"for_android\":{\"status\":1,\"version\":\"14.1\",\"link\":\"\"},\"for_ios\":{\"status\":1,\"version\":\"14.1\",\"link\":\"\"}}', NULL, '2023-10-13 11:34:53'),
(121, 'seller_app_version_control', '{\"for_android\":{\"status\":1,\"version\":\"14.1\",\"link\":\"\"},\"for_ios\":{\"status\":1,\"version\":\"14.1\",\"link\":\"\"}}', NULL, '2023-10-13 11:34:53'),
(122, 'delivery_man_app_version_control', '{\"for_android\":{\"status\":1,\"version\":\"14.1\",\"link\":\"\"},\"for_ios\":{\"status\":1,\"version\":\"14.1\",\"link\":\"\"}}', NULL, '2023-10-13 11:34:53'),
(123, 'whatsapp', '{\"status\":1,\"phone\":\"00000000000\"}', NULL, '2023-10-13 11:34:53'),
(124, 'currency_symbol_position', 'right', NULL, '2024-08-07 21:56:03'),
(148, 'company_reliability', '[{\"item\":\"delivery_info\",\"title\":\"Fast Delivery all across the country\",\"image\":\"\",\"status\":1},{\"item\":\"safe_payment\",\"title\":\"Safe Payment\",\"image\":\"\",\"status\":1},{\"item\":\"return_policy\",\"title\":\"7 Days Return Policy\",\"image\":\"\",\"status\":1},{\"item\":\"authentic_product\",\"title\":\"100% Authentic Products\",\"image\":\"\",\"status\":1}]', NULL, NULL),
(149, 'react_setup', '{\"status\":0,\"react_license_code\":\"\",\"react_domain\":\"\",\"react_platform\":\"\"}', NULL, '2024-01-09 04:05:15'),
(150, 'app_activation', '{\"software_id\":\"\",\"is_active\":0}', NULL, '2024-08-08 03:41:30'),
(151, 'shop_banner', '{\"image_name\":\"2024-08-08-66b3eb91bad94.webp\",\"storage\":\"public\"}', NULL, '2024-08-08 03:48:01'),
(152, 'map_api_status', '1', NULL, '2024-08-08 03:41:30'),
(153, 'vendor_registration_header', '{\"title\":\"Vendor Registration\",\"sub_title\":\"Create your own store.Already have store?\",\"image\":\"\"}', NULL, NULL),
(154, 'vendor_registration_sell_with_us', '{\"title\":\"Why Sell With Us\",\"sub_title\":\"Boost your sales! Join us for a seamless, profitable experience with vast buyer reach and top-notch support. Sell smarter today!\",\"image\":\"\"}', NULL, NULL),
(155, 'download_vendor_app', '{\"title\":\"Download Free Vendor App\",\"sub_title\":\"Download our free seller app and start reaching millions of buyers on the go! Easy setup, manage listings, and boost sales anywhere.\",\"image\":null,\"download_google_app\":null,\"download_google_app_status\":0,\"download_apple_app\":null,\"download_apple_app_status\":0}', NULL, NULL),
(156, 'business_process_main_section', '{\"title\":\"3 Easy Steps To Start Selling\",\"sub_title\":\"Start selling quickly! Register, upload your products with detailed info and images, and reach millions of buyers instantly.\",\"image\":\"\"}', NULL, NULL),
(157, 'business_process_step', '[{\"title\":\"Get Registered\",\"description\":\"Sign up easily and create your seller account in just a few minutes. It fast and simple to get started.\",\"image\":\"\"},{\"title\":\"Upload Products\",\"description\":\"List your products with detailed descriptions and high-quality images to attract more buyers effortlessly.\",\"image\":\"\"},{\"title\":\"Start Selling\",\"description\":\"Go live and start reaching millions of potential buyers immediately. Watch your sales grow with our vast audience.\",\"image\":\"\"}]', NULL, NULL),
(158, 'brand_list_priority', '', '2024-05-18 10:57:03', '2024-05-18 10:57:03'),
(159, 'category_list_priority', '', '2024-05-18 10:57:03', '2024-05-18 10:57:03'),
(160, 'vendor_list_priority', '', '2024-05-18 10:57:03', '2024-05-18 10:57:03'),
(161, 'flash_deal_priority', '', '2024-05-18 10:57:03', '2024-05-18 10:57:03'),
(162, 'featured_product_priority', '', '2024-05-18 10:57:03', '2024-05-18 10:57:03'),
(163, 'feature_deal_priority', '', '2024-05-18 10:57:03', '2024-05-18 10:57:03'),
(164, 'new_arrival_product_list_priority', '', '2024-05-18 10:57:03', '2024-05-18 10:57:03'),
(165, 'top_vendor_list_priority', '', '2024-05-18 10:57:03', '2024-05-18 10:57:03'),
(166, 'category_wise_product_list_priority', '', '2024-05-18 10:57:03', '2024-05-18 10:57:03'),
(167, 'top_rated_product_list_priority', '', '2024-05-18 10:57:03', '2024-05-18 10:57:03'),
(168, 'best_selling_product_list_priority', '', '2024-05-18 10:57:03', '2024-05-18 10:57:03'),
(169, 'searched_product_list_priority', '', '2024-05-18 10:57:03', '2024-05-18 10:57:03'),
(170, 'vendor_product_list_priority', '', '2024-05-18 10:57:03', '2024-05-18 10:57:03'),
(171, 'storage_connection_type', 'public', NULL, '2024-08-08 03:41:30'),
(172, 'google_search_console_code', '', NULL, '2024-08-08 03:41:30'),
(173, 'bing_webmaster_code', '', NULL, '2024-08-08 03:41:30'),
(174, 'baidu_webmaster_code', '', NULL, '2024-08-08 03:41:30'),
(175, 'yandex_webmaster_code', '', NULL, '2024-08-08 03:41:30'),
(176, 'timezone', 'UTC', NULL, '2024-08-07 21:56:03'),
(177, 'default_location', '{\"lat\":\"-33.8688\",\"lng\":\"151.2195\"}', NULL, '2024-08-07 21:56:03');

-- --------------------------------------------------------

--
-- Structure de la table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) DEFAULT NULL,
  `cart_group_id` varchar(191) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `product_type` varchar(20) NOT NULL DEFAULT 'physical',
  `digital_product_type` varchar(30) DEFAULT NULL,
  `color` varchar(191) DEFAULT NULL,
  `choices` text DEFAULT NULL,
  `variations` text DEFAULT NULL,
  `variant` text DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` double NOT NULL DEFAULT 1,
  `tax` double NOT NULL DEFAULT 1,
  `discount` double NOT NULL DEFAULT 1,
  `tax_model` varchar(20) NOT NULL DEFAULT 'exclude',
  `is_checked` tinyint(1) NOT NULL DEFAULT 0,
  `slug` varchar(191) DEFAULT NULL,
  `name` varchar(191) DEFAULT NULL,
  `thumbnail` varchar(191) DEFAULT NULL,
  `seller_id` bigint(20) DEFAULT NULL,
  `seller_is` varchar(191) NOT NULL DEFAULT 'admin',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `shop_info` varchar(191) DEFAULT NULL,
  `shipping_cost` double(8,2) DEFAULT NULL,
  `shipping_type` varchar(191) DEFAULT NULL,
  `is_guest` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `carts`
--

INSERT INTO `carts` (`id`, `customer_id`, `cart_group_id`, `product_id`, `product_type`, `digital_product_type`, `color`, `choices`, `variations`, `variant`, `quantity`, `price`, `tax`, `discount`, `tax_model`, `is_checked`, `slug`, `name`, `thumbnail`, `seller_id`, `seller_is`, `created_at`, `updated_at`, `shop_info`, `shipping_cost`, `shipping_type`, `is_guest`) VALUES
(3, 33, '33-6SBAb-1723726387', 1, 'physical', NULL, NULL, '[]', '[]', '', 1, 1200, 0, 1000, 'include', 1, 'aluminium-pocket-9dYcyr', 'Aluminium Pocket', '2024-08-09-66b6327ef1fbd.webp', 1, 'admin', '2024-08-15 21:49:02', '2024-08-15 21:49:02', 'Nichen', 0.00, 'order_wise', 0),
(5, 33, '33-7Ryvf-1723736996', 3, 'physical', NULL, NULL, '[]', '[]', '', 1, 1200, 0, 1000, 'include', 0, 'shoes-jqbG7c', 'Shoes', '2024-08-11-66b92f9225b5b.webp', 1, 'seller', '2024-08-15 19:35:56', '2024-08-15 21:49:02', 'GameHub', 0.00, 'order_wise', 0),
(6, 25, '25-2pTye-1723747656', 4, 'physical', NULL, '#A52A2A', '{\"choice_1\":\"XL\"}', '{\"color\":\"Brown\",\"Sizes\":\"XL\"}', 'Brown-XL', 1, 1000, 0, 0, 'include', 0, 'mens-fleece-lined-hoodie-with-bear-print-and-drawstring-ydksHd', 'Men\'s Fleece Lined Hoodie With Bear Print And Drawstring', '2024-08-15-66be2763430c7.webp', 1, 'admin', '2024-08-15 18:47:37', '2024-08-15 23:05:13', 'Nichen', 0.00, 'order_wise', 0),
(9, 33, '33-6SBAb-1723726387', 4, 'physical', NULL, '#000000', '{\"choice_1\":\"M\"}', '{\"color\":\"Black\",\"Sizes\":\"M\"}', 'Black-M', 1, 1000, 0, 0, 'include', 0, 'mens-fleece-lined-hoodie-with-bear-print-and-drawstring-ydksHd', 'Men\'s Fleece Lined Hoodie With Bear Print And Drawstring', '2024-08-15-66be2763430c7.webp', 1, 'admin', '2024-08-15 20:29:46', '2024-08-15 21:49:02', 'Nichen', 0.00, 'order_wise', 0),
(11, 198, 'guest-AxsDp-1723830495', 1, 'physical', NULL, NULL, '[]', '[]', '', 1, 1200, 0, 1000, 'include', 1, 'aluminium-pocket-9dYcyr', 'Aluminium Pocket', '2024-08-09-66b6327ef1fbd.webp', 1, 'admin', '2024-08-16 17:48:20', '2024-08-16 17:48:20', 'Nichen', 0.00, 'order_wise', 1),
(12, 197, 'guest-AZ0Dk-1723831756', 4, 'physical', NULL, '#A52A2A', '{\"choice_1\":\"M\"}', '{\"color\":\"Brown\",\"Sizes\":\"M\"}', 'Brown-M', 1, 1000, 0, 0, 'include', 1, 'mens-fleece-lined-hoodie-with-bear-print-and-drawstring-ydksHd', 'Men\'s Fleece Lined Hoodie With Bear Print And Drawstring', '2024-08-15-66be2763430c7.webp', 1, 'admin', '2024-08-16 18:09:16', '2024-08-16 18:09:16', 'Nichen', 0.00, 'order_wise', 1);

-- --------------------------------------------------------

--
-- Structure de la table `cart_shippings`
--

CREATE TABLE `cart_shippings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cart_group_id` varchar(191) DEFAULT NULL,
  `shipping_method_id` bigint(20) DEFAULT NULL,
  `shipping_cost` double(8,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `cart_shippings`
--

INSERT INTO `cart_shippings` (`id`, `cart_group_id`, `shipping_method_id`, `shipping_cost`, `created_at`, `updated_at`) VALUES
(70, '33-6SBAb-1723726387', 2, 5.00, '2024-08-15 21:49:02', '2024-08-15 21:49:02'),
(73, 'guest-AxsDp-1723830495', 2, 5.00, '2024-08-16 17:48:20', '2024-08-16 17:48:20');

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `icon` varchar(250) DEFAULT NULL,
  `icon_storage_type` varchar(10) DEFAULT 'public',
  `parent_id` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `home_status` tinyint(1) NOT NULL DEFAULT 0,
  `priority` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `icon_storage_type`, `parent_id`, `position`, `created_at`, `updated_at`, `home_status`, `priority`) VALUES
(1, 'Shoes', 'shoes', '2024-08-08-66b3eb4d40d28.webp', 'public', 0, 0, '2024-08-08 03:46:53', '2024-08-15 13:51:44', 1, 0),
(2, 'Automobiles', 'automobiles', '2024-08-15-66be07e86465b.webp', 'public', 0, 0, '2024-08-15 13:51:36', '2024-08-15 13:51:42', 1, 1),
(3, 'Phones', 'phones', '2024-08-15-66be0814dc6fa.svg', 'public', 0, 0, '2024-08-15 13:52:20', '2024-08-15 13:54:16', 1, 1),
(4, 'Rents', 'rents', '2024-08-15-66be084cb94ae.svg', 'public', 0, 0, '2024-08-15 13:53:16', '2024-08-15 13:54:10', 1, 1),
(5, 'Pc\'s', 'pcs', '2024-08-15-66be0865530fa.svg', 'public', 0, 0, '2024-08-15 13:53:41', '2024-08-15 13:54:07', 1, 1),
(6, 'AirPods', 'airpods', '2024-08-15-66be0878687cc.svg', 'public', 3, 1, '2024-08-15 13:54:00', '2024-08-15 13:54:00', 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `category_shipping_costs`
--

CREATE TABLE `category_shipping_costs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `seller_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `cost` double(8,2) DEFAULT NULL,
  `multiply_qty` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `category_shipping_costs`
--

INSERT INTO `category_shipping_costs` (`id`, `seller_id`, `category_id`, `cost`, `multiply_qty`, `created_at`, `updated_at`) VALUES
(1, 0, 1, 0.00, NULL, '2024-08-15 13:06:50', '2024-08-15 13:06:50');

-- --------------------------------------------------------

--
-- Structure de la table `chattings`
--

CREATE TABLE `chattings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `seller_id` bigint(20) DEFAULT NULL,
  `admin_id` bigint(20) DEFAULT NULL,
  `delivery_man_id` bigint(20) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `attachment` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachment`)),
  `sent_by_customer` tinyint(1) NOT NULL DEFAULT 0,
  `sent_by_seller` tinyint(1) NOT NULL DEFAULT 0,
  `sent_by_admin` tinyint(1) DEFAULT NULL,
  `sent_by_delivery_man` tinyint(1) DEFAULT NULL,
  `seen_by_customer` tinyint(1) NOT NULL DEFAULT 1,
  `seen_by_seller` tinyint(1) NOT NULL DEFAULT 1,
  `seen_by_admin` tinyint(1) DEFAULT NULL,
  `seen_by_delivery_man` tinyint(1) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `notification_receiver` varchar(20) DEFAULT NULL COMMENT 'admin, seller, customer, deliveryman',
  `seen_notification` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `shop_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `chattings`
--

INSERT INTO `chattings` (`id`, `user_id`, `seller_id`, `admin_id`, `delivery_man_id`, `message`, `attachment`, `sent_by_customer`, `sent_by_seller`, `sent_by_admin`, `sent_by_delivery_man`, `seen_by_customer`, `seen_by_seller`, `seen_by_admin`, `seen_by_delivery_man`, `status`, `notification_receiver`, `seen_notification`, `created_at`, `updated_at`, `shop_id`) VALUES
(1, 25, NULL, 0, NULL, 'test', '[]', 1, 0, NULL, NULL, 1, 0, NULL, NULL, 1, 'admin', 1, '2024-08-08 02:23:58', '2024-08-15 15:43:25', NULL),
(2, 25, NULL, 0, NULL, 'hello', '[]', 1, 0, NULL, NULL, 1, 0, NULL, NULL, 1, 'admin', 1, '2024-08-08 02:24:03', '2024-08-15 15:43:25', NULL),
(3, 25, NULL, 0, NULL, 'sell me something', '[]', 1, 0, NULL, NULL, 1, 0, NULL, NULL, 1, 'admin', 1, '2024-08-08 02:24:10', '2024-08-15 15:43:25', NULL),
(4, 33, NULL, 0, NULL, 'slm', '[]', 1, 0, NULL, NULL, 1, 0, NULL, NULL, 1, 'admin', 1, '2024-08-13 07:58:53', '2024-08-15 15:43:58', NULL),
(5, 33, NULL, 0, NULL, NULL, '[{\"file_name\":\"2024-08-13-66bb4cf4e94cc.webp\",\"storage\":\"public\"}]', 1, 0, NULL, NULL, 1, 0, NULL, NULL, 1, 'admin', 1, '2024-08-13 12:09:24', '2024-08-15 15:43:58', NULL),
(6, 25, NULL, 0, NULL, 'test', '[]', 1, 0, NULL, NULL, 1, 0, NULL, NULL, 1, 'admin', 1, '2024-08-15 15:43:29', '2024-08-15 15:45:24', NULL),
(7, 25, NULL, 0, NULL, 'okey', '[]', 1, 0, NULL, NULL, 1, 0, NULL, NULL, 1, 'admin', 1, '2024-08-15 19:09:10', '2024-08-15 19:09:59', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `colors`
--

CREATE TABLE `colors` (
  `id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `colors`
--

INSERT INTO `colors` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(1, 'IndianRed', '#CD5C5C', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(2, 'LightCoral', '#F08080', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(3, 'Salmon', '#FA8072', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(4, 'DarkSalmon', '#E9967A', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(5, 'LightSalmon', '#FFA07A', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(6, 'Crimson', '#DC143C', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(7, 'Red', '#FF0000', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(8, 'FireBrick', '#B22222', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(9, 'DarkRed', '#8B0000', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(10, 'Pink', '#FFC0CB', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(11, 'LightPink', '#FFB6C1', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(12, 'HotPink', '#FF69B4', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(13, 'DeepPink', '#FF1493', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(14, 'MediumVioletRed', '#C71585', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(15, 'PaleVioletRed', '#DB7093', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(17, 'Coral', '#FF7F50', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(18, 'Tomato', '#FF6347', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(19, 'OrangeRed', '#FF4500', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(20, 'DarkOrange', '#FF8C00', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(21, 'Orange', '#FFA500', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(22, 'Gold', '#FFD700', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(23, 'Yellow', '#FFFF00', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(24, 'LightYellow', '#FFFFE0', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(25, 'LemonChiffon', '#FFFACD', '2018-11-05 02:12:26', '2018-11-05 02:12:26'),
(26, 'LightGoldenrodYellow', '#FAFAD2', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(27, 'PapayaWhip', '#FFEFD5', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(28, 'Moccasin', '#FFE4B5', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(29, 'PeachPuff', '#FFDAB9', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(30, 'PaleGoldenrod', '#EEE8AA', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(31, 'Khaki', '#F0E68C', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(32, 'DarkKhaki', '#BDB76B', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(33, 'Lavender', '#E6E6FA', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(34, 'Thistle', '#D8BFD8', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(35, 'Plum', '#DDA0DD', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(36, 'Violet', '#EE82EE', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(37, 'Orchid', '#DA70D6', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(39, 'Magenta', '#FF00FF', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(40, 'MediumOrchid', '#BA55D3', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(41, 'MediumPurple', '#9370DB', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(42, 'Amethyst', '#9966CC', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(43, 'BlueViolet', '#8A2BE2', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(44, 'DarkViolet', '#9400D3', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(45, 'DarkOrchid', '#9932CC', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(46, 'DarkMagenta', '#8B008B', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(47, 'Purple', '#800080', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(48, 'Indigo', '#4B0082', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(49, 'SlateBlue', '#6A5ACD', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(50, 'DarkSlateBlue', '#483D8B', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(51, 'MediumSlateBlue', '#7B68EE', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(52, 'GreenYellow', '#ADFF2F', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(53, 'Chartreuse', '#7FFF00', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(54, 'LawnGreen', '#7CFC00', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(55, 'Lime', '#00FF00', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(56, 'LimeGreen', '#32CD32', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(57, 'PaleGreen', '#98FB98', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(58, 'LightGreen', '#90EE90', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(59, 'MediumSpringGreen', '#00FA9A', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(60, 'SpringGreen', '#00FF7F', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(61, 'MediumSeaGreen', '#3CB371', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(62, 'SeaGreen', '#2E8B57', '2018-11-05 02:12:27', '2018-11-05 02:12:27'),
(63, 'ForestGreen', '#228B22', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(64, 'Green', '#008000', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(65, 'DarkGreen', '#006400', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(66, 'YellowGreen', '#9ACD32', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(67, 'OliveDrab', '#6B8E23', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(68, 'Olive', '#808000', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(69, 'DarkOliveGreen', '#556B2F', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(70, 'MediumAquamarine', '#66CDAA', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(71, 'DarkSeaGreen', '#8FBC8F', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(72, 'LightSeaGreen', '#20B2AA', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(73, 'DarkCyan', '#008B8B', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(74, 'Teal', '#008080', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(75, 'Aqua', '#00FFFF', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(77, 'LightCyan', '#E0FFFF', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(78, 'PaleTurquoise', '#AFEEEE', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(79, 'Aquamarine', '#7FFFD4', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(80, 'Turquoise', '#40E0D0', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(81, 'MediumTurquoise', '#48D1CC', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(82, 'DarkTurquoise', '#00CED1', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(83, 'CadetBlue', '#5F9EA0', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(84, 'SteelBlue', '#4682B4', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(85, 'LightSteelBlue', '#B0C4DE', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(86, 'PowderBlue', '#B0E0E6', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(87, 'LightBlue', '#ADD8E6', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(88, 'SkyBlue', '#87CEEB', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(89, 'LightSkyBlue', '#87CEFA', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(90, 'DeepSkyBlue', '#00BFFF', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(91, 'DodgerBlue', '#1E90FF', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(92, 'CornflowerBlue', '#6495ED', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(94, 'RoyalBlue', '#4169E1', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(95, 'Blue', '#0000FF', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(96, 'MediumBlue', '#0000CD', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(97, 'DarkBlue', '#00008B', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(98, 'Navy', '#000080', '2018-11-05 02:12:28', '2018-11-05 02:12:28'),
(99, 'MidnightBlue', '#191970', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(100, 'Cornsilk', '#FFF8DC', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(101, 'BlanchedAlmond', '#FFEBCD', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(102, 'Bisque', '#FFE4C4', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(103, 'NavajoWhite', '#FFDEAD', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(104, 'Wheat', '#F5DEB3', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(105, 'BurlyWood', '#DEB887', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(106, 'Tan', '#D2B48C', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(107, 'RosyBrown', '#BC8F8F', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(108, 'SandyBrown', '#F4A460', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(109, 'Goldenrod', '#DAA520', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(110, 'DarkGoldenrod', '#B8860B', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(111, 'Peru', '#CD853F', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(112, 'Chocolate', '#D2691E', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(113, 'SaddleBrown', '#8B4513', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(114, 'Sienna', '#A0522D', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(115, 'Brown', '#A52A2A', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(116, 'Maroon', '#800000', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(117, 'White', '#FFFFFF', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(118, 'Snow', '#FFFAFA', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(119, 'Honeydew', '#F0FFF0', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(120, 'MintCream', '#F5FFFA', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(121, 'Azure', '#F0FFFF', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(122, 'AliceBlue', '#F0F8FF', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(123, 'GhostWhite', '#F8F8FF', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(124, 'WhiteSmoke', '#F5F5F5', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(125, 'Seashell', '#FFF5EE', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(126, 'Beige', '#F5F5DC', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(127, 'OldLace', '#FDF5E6', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(128, 'FloralWhite', '#FFFAF0', '2018-11-05 02:12:29', '2018-11-05 02:12:29'),
(129, 'Ivory', '#FFFFF0', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
(130, 'AntiqueWhite', '#FAEBD7', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
(131, 'Linen', '#FAF0E6', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
(132, 'LavenderBlush', '#FFF0F5', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
(133, 'MistyRose', '#FFE4E1', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
(134, 'Gainsboro', '#DCDCDC', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
(135, 'LightGrey', '#D3D3D3', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
(136, 'Silver', '#C0C0C0', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
(137, 'DarkGray', '#A9A9A9', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
(138, 'Gray', '#808080', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
(139, 'DimGray', '#696969', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
(140, 'LightSlateGray', '#778899', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
(141, 'SlateGray', '#708090', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
(142, 'DarkSlateGray', '#2F4F4F', '2018-11-05 02:12:30', '2018-11-05 02:12:30'),
(143, 'Black', '#000000', '2018-11-05 02:12:30', '2018-11-05 02:12:30');

-- --------------------------------------------------------

--
-- Structure de la table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `mobile_number` varchar(191) NOT NULL,
  `subject` varchar(191) NOT NULL,
  `message` text NOT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT 0,
  `feedback` varchar(191) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `reply` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `added_by` varchar(191) NOT NULL DEFAULT 'admin',
  `coupon_type` varchar(50) DEFAULT NULL,
  `coupon_bearer` varchar(191) NOT NULL DEFAULT 'inhouse',
  `seller_id` bigint(20) DEFAULT NULL COMMENT 'NULL=in-house, 0=all seller',
  `customer_id` bigint(20) DEFAULT NULL COMMENT '0 = all customer',
  `title` varchar(100) DEFAULT NULL,
  `code` varchar(15) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `expire_date` date DEFAULT NULL,
  `min_purchase` decimal(8,2) NOT NULL DEFAULT 0.00,
  `max_discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `discount_type` varchar(15) NOT NULL DEFAULT 'percentage',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `limit` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `currencies`
--

CREATE TABLE `currencies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `symbol` varchar(191) NOT NULL,
  `code` varchar(191) NOT NULL,
  `exchange_rate` varchar(191) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `symbol`, `code`, `exchange_rate`, `status`, `created_at`, `updated_at`) VALUES
(1, 'USD', '$', 'USD', '1', 0, NULL, '2024-08-08 03:50:03'),
(2, 'BDT', '৳', 'BDT', '84', 0, NULL, '2024-08-08 03:49:43'),
(3, 'Indian Rupi', '₹', 'INR', '60', 0, '2020-10-15 17:23:04', '2024-08-08 03:49:41'),
(4, 'Euro', '€', 'EUR', '100', 0, '2021-05-25 21:00:23', '2024-08-08 03:49:39'),
(5, 'YEN', '¥', 'JPY', '110', 0, '2021-06-10 22:08:31', '2024-08-08 03:49:38'),
(6, 'Ringgit', 'RM', 'MYR', '4.16', 0, '2021-07-03 11:08:33', '2024-08-08 03:49:36'),
(7, 'Rand', 'R', 'ZAR', '14.26', 0, '2021-07-03 11:12:38', '2024-08-08 03:49:34'),
(8, 'Dinar Algerian', 'DA', 'DZD', '1', 1, '2024-08-08 03:49:22', '2024-08-08 03:49:45');

-- --------------------------------------------------------

--
-- Structure de la table `customer_wallets`
--

CREATE TABLE `customer_wallets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) DEFAULT NULL,
  `balance` decimal(8,2) NOT NULL DEFAULT 0.00,
  `royality_points` decimal(8,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `customer_wallet_histories`
--

CREATE TABLE `customer_wallet_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) DEFAULT NULL,
  `transaction_amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `transaction_type` varchar(20) DEFAULT NULL,
  `transaction_method` varchar(30) DEFAULT NULL,
  `transaction_id` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `deal_of_the_days`
--

CREATE TABLE `deal_of_the_days` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `discount_type` varchar(12) NOT NULL DEFAULT 'amount',
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `deliveryman_notifications`
--

CREATE TABLE `deliveryman_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `delivery_man_id` bigint(20) NOT NULL,
  `order_id` bigint(20) NOT NULL,
  `description` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `deliveryman_wallets`
--

CREATE TABLE `deliveryman_wallets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `delivery_man_id` bigint(20) NOT NULL,
  `current_balance` decimal(50,2) NOT NULL DEFAULT 0.00,
  `cash_in_hand` decimal(50,2) NOT NULL DEFAULT 0.00,
  `pending_withdraw` decimal(50,2) NOT NULL DEFAULT 0.00,
  `total_withdraw` decimal(50,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `delivery_country_codes`
--

CREATE TABLE `delivery_country_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `country_code` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `delivery_histories`
--

CREATE TABLE `delivery_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) DEFAULT NULL,
  `deliveryman_id` bigint(20) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `longitude` varchar(191) DEFAULT NULL,
  `latitude` varchar(191) DEFAULT NULL,
  `location` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `delivery_man_transactions`
--

CREATE TABLE `delivery_man_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `delivery_man_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `user_type` varchar(20) NOT NULL,
  `transaction_id` char(36) NOT NULL,
  `debit` decimal(50,2) NOT NULL DEFAULT 0.00,
  `credit` decimal(50,2) NOT NULL DEFAULT 0.00,
  `transaction_type` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `delivery_men`
--

CREATE TABLE `delivery_men` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `seller_id` bigint(20) DEFAULT NULL,
  `f_name` varchar(100) DEFAULT NULL,
  `l_name` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `country_code` varchar(20) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `identity_number` varchar(30) DEFAULT NULL,
  `identity_type` varchar(50) DEFAULT NULL,
  `identity_image` varchar(191) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `bank_name` varchar(191) DEFAULT NULL,
  `branch` varchar(191) DEFAULT NULL,
  `account_no` varchar(191) DEFAULT NULL,
  `holder_name` varchar(191) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_online` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `auth_token` varchar(191) NOT NULL DEFAULT '6yIRXJRRfp78qJsAoKZZ6TTqhzuNJ3TcdvPBmk6n',
  `fcm_token` varchar(191) DEFAULT NULL,
  `app_language` varchar(191) NOT NULL DEFAULT 'en'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `delivery_zip_codes`
--

CREATE TABLE `delivery_zip_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `zipcode` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `digital_product_otp_verifications`
--

CREATE TABLE `digital_product_otp_verifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_details_id` varchar(255) DEFAULT NULL,
  `identity` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `otp_hit_count` tinyint(4) NOT NULL DEFAULT 0,
  `is_temp_blocked` tinyint(1) NOT NULL DEFAULT 0,
  `temp_block_time` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `digital_product_variations`
--

CREATE TABLE `digital_product_variations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `variant_key` varchar(255) DEFAULT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `price` decimal(24,8) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `template_name` varchar(191) NOT NULL,
  `user_type` varchar(191) NOT NULL,
  `template_design_name` varchar(191) NOT NULL,
  `title` varchar(191) DEFAULT NULL,
  `body` text DEFAULT NULL,
  `banner_image` varchar(191) DEFAULT NULL,
  `image` varchar(191) DEFAULT NULL,
  `logo` varchar(191) DEFAULT NULL,
  `button_name` varchar(191) DEFAULT NULL,
  `button_url` varchar(191) DEFAULT NULL,
  `footer_text` varchar(191) DEFAULT NULL,
  `copyright_text` varchar(191) DEFAULT NULL,
  `pages` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`pages`)),
  `social_media` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`social_media`)),
  `hide_field` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`hide_field`)),
  `button_content_status` tinyint(4) NOT NULL DEFAULT 1,
  `product_information_status` tinyint(4) NOT NULL DEFAULT 1,
  `order_information_status` tinyint(4) NOT NULL DEFAULT 1,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `email_templates`
--

INSERT INTO `email_templates` (`id`, `template_name`, `user_type`, `template_design_name`, `title`, `body`, `banner_image`, `image`, `logo`, `button_name`, `button_url`, `footer_text`, `copyright_text`, `pages`, `social_media`, `hide_field`, `button_content_status`, `product_information_status`, `order_information_status`, `status`, `created_at`, `updated_at`) VALUES
(1, 'order-received', 'admin', 'order-received', 'New Order Received', '<p><b>Hi {adminName},</b></p><p>We have sent you this email to notify that you have a new order.You will be able to see your orders after login to your panel.</p>', NULL, NULL, NULL, NULL, NULL, 'Please contact us for any queries, we’re always happy to help.', 'Copyright 2024 Nichen. All right reserved.', NULL, NULL, '[\"icon\",\"product_information\",\"button_content\",\"banner_image\"]', 1, 1, 1, 1, '2024-08-08 03:41:30', '2024-08-08 03:41:30'),
(2, 'order-place', 'customer', 'order-place', 'Order # {orderId} Has Been Placed Successfully!', '<p><b>Hi {userName},</b></p><p>Your order from {shopName} has been placed to know the current status of your order click track order</p>', NULL, NULL, NULL, NULL, NULL, 'Please contact us for any queries, we’re always happy to help.', 'Copyright 2024 Nichen. All right reserved.', NULL, NULL, '[\"icon\",\"product_information\",\"banner_image\"]', 1, 1, 1, 1, '2024-08-08 03:41:30', '2024-08-08 03:41:30'),
(3, 'forgot-password', 'customer', 'forgot-password', 'Change Password Request', '<p><b>Hi {userName},</b></p><p>Please click the link below to change your password.</p>', NULL, NULL, NULL, NULL, NULL, 'Please contact us for any queries, we’re always happy to help.', 'Copyright 2024 Nichen. All right reserved.', NULL, NULL, '[\"product_information\",\"order_information\",\"button_content\",\"banner_image\"]', 1, 1, 1, 1, '2024-08-08 03:41:30', '2024-08-08 03:41:30'),
(4, 'registration-verification', 'customer', 'registration-verification', 'Registration Verification', '<p><b>Hi {userName},</b></p><p>Your verification code is</p>', NULL, NULL, NULL, NULL, NULL, 'Please contact us for any queries, we’re always happy to help.', 'Copyright 2024 Nichen. All right reserved.', NULL, NULL, '[\"product_information\",\"order_information\",\"button_content\",\"banner_image\"]', 1, 1, 1, 1, '2024-08-08 03:41:30', '2024-08-08 03:41:30'),
(5, 'registration-from-pos', 'customer', 'registration-from-pos', 'Registration Complete', '<p><b>Hi {userName},</b></p><p>Thank you for joining Nichen Shop.If you want to become a registered customer then reset your password below by using this email. Then you’ll be able to explore the website and app as a registered customer.</p>', NULL, NULL, NULL, NULL, NULL, 'Please contact us for any queries, we’re always happy to help.', 'Copyright 2024 Nichen. All right reserved.', NULL, NULL, '[\"product_information\",\"order_information\",\"button_url\",\"button_content_status\",\"banner_image\"]', 1, 1, 1, 1, '2024-08-08 03:41:30', '2024-08-08 03:41:30'),
(6, 'account-block', 'customer', 'account-block', 'Account Blocked', '<div><b>Hi {userName},</b></div><div><b><br></b></div><div>Your account has been blocked due to suspicious activity by the admin .To resolve this issue please contact with admin or support center. We apologize for any inconvenience caused.</div><div><br></div><div>Meanwhile, click here to visit theNichenshop website</div><div><font color=\"#0000ff\"> <a href=\"https://nichen.net\" target=\"_blank\">https://nichen.net</a></font></div>', NULL, NULL, NULL, NULL, NULL, 'Please contact us for any queries, we’re always happy to help.', 'Copyright 2024 Nichen. All right reserved.', NULL, NULL, '[\"product_information\",\"order_information\",\"button_content\",\"banner_image\"]', 1, 1, 1, 1, '2024-08-08 03:41:30', '2024-08-08 03:41:30'),
(7, 'account-unblock', 'customer', 'account-unblock', 'Account Unblocked', '<div><b>Hi {userName},</b></div><div><b><br></b></div><div>Your account has been successfully unblocked. We appreciate your cooperation in resolving this issue. Thank you for your understanding and patience. </div><div><br></div><div>Meanwhile, click here to visit theNichen shop website</div><div><font color=\"#0000ff\"> <a href=\"https://nichen.net\" target=\"_blank\">https://nichen.net</a></font></div>', NULL, NULL, NULL, NULL, NULL, 'Please contact us for any queries, we’re always happy to help.', 'Copyright 2024 Nichen. All right reserved.', NULL, NULL, '[\"product_information\",\"order_information\",\"button_content\",\"banner_image\"]', 1, 1, 1, 1, '2024-08-08 03:41:30', '2024-08-08 03:41:30'),
(8, 'digital-product-download', 'customer', 'digital-product-download', 'Congratulations', '<p>Thank you for choosing Nichen shop! Your digital product is ready for download. To download your product use your email <b>{emailId}</b> and order # {orderId} below.</b><br></p>', NULL, NULL, NULL, NULL, NULL, 'Please contact us for any queries, we’re always happy to help.', 'Copyright 2024 Nichen. All right reserved.', NULL, NULL, '[\"product_information\",\"button_content\",\"banner_image\"]', 1, 1, 1, 1, '2024-08-08 03:41:30', '2024-08-08 03:41:30'),
(9, 'digital-product-otp', 'customer', 'digital-product-otp', 'Digital Product Download OTP Verification', '<p><b>Hi {userName},</b></p><p>Your verification code is</p>', NULL, NULL, NULL, NULL, NULL, 'Please contact us for any queries, we’re always happy to help.', 'Copyright 2024 Nichen. All right reserved.', NULL, NULL, '[\"product_information\",\"order_information\",\"button_content\",\"banner_image\"]', 1, 1, 1, 1, '2024-08-08 03:41:30', '2024-08-08 03:41:30'),
(10, 'add-fund-to-wallet', 'customer', 'add-fund-to-wallet', 'Transaction Successful', '<div style=\"text-align: center; \">Amount successfully credited to your wallet .</div><div style=\"text-align: center; \"><br></div>', NULL, NULL, NULL, NULL, NULL, 'Please contact us for any queries, we’re always happy to help.', 'Copyright 2024 Nichen. All right reserved.', NULL, NULL, '[\"product_information\",\"order_information\",\"button_content\",\"banner_image\"]', 1, 1, 1, 1, '2024-08-08 03:41:30', '2024-08-08 03:41:30'),
(11, 'registration', 'vendor', 'registration', 'Registration Complete', '<div><b>Hi {vendorName},</b></div><div><b><br></b></div><div>Congratulation! Your registration request has been send to admin successfully! Please wait until admin reviewal. </div><div><br></div><div>meanwhile click here to visit the Nichen Shop Website</div><div><font color=\"#0000ff\"> <a href=\"https://nichen.net\" target=\"_blank\">https://nichen.net</a></font></div>', NULL, NULL, NULL, NULL, NULL, 'Please contact us for any queries, we’re always happy to help.', 'Copyright 2024 Nichen. All right reserved.', NULL, NULL, '[\"product_information\",\"order_information\",\"button_content\",\"banner_image\"]', 1, 1, 1, 1, '2024-08-08 03:41:30', '2024-08-08 03:41:30'),
(12, 'registration-approved', 'vendor', 'registration-approved', 'Registration Approved', '<div><b>Hi {vendorName},</b></div><div><b><br></b></div><div>Your registration request has been approved by admin. Now you can complete your store setting and start selling your product on Nichen Shop. </div><div><br></div><div>Meanwhile, click here to visit theNichen shop website</div><div><font color=\"#0000ff\"> <a href=\"https://nichen.net\" target=\"_blank\">https://nichen.net</a></font></div>', NULL, NULL, NULL, NULL, NULL, 'Please contact us for any queries, we’re always happy to help.', 'Copyright 2024 Nichen. All right reserved.', NULL, NULL, '[\"product_information\",\"order_information\",\"button_content\",\"banner_image\"]', 1, 1, 1, 1, '2024-08-08 03:41:30', '2024-08-08 03:41:30'),
(13, 'registration-denied', 'vendor', 'registration-denied', 'Registration Denied', '<div><b>Hi {vendorName},</b></div><div><b><br></b></div><div>Your registration request has been denied by admin. Please contact with admin or support center if you have any queries.</div><div><br></div><div>Meanwhile, click here to visit theNichen shop website</div><div><font color=\"#0000ff\"> <a href=\"https://nichen.net\" target=\"_blank\">https://nichen.net</a></font></div>', NULL, NULL, NULL, NULL, NULL, 'Please contact us for any queries, we’re always happy to help.', 'Copyright 2024 Nichen. All right reserved.', NULL, NULL, '[\"product_information\",\"order_information\",\"button_content\",\"banner_image\"]', 1, 1, 1, 1, '2024-08-08 03:41:30', '2024-08-08 03:41:30'),
(14, 'account-suspended', 'vendor', 'account-suspended', 'Account Suspended', '<div><b>Hi {vendorName},</b></div><div><b><br></b></div><div>Your account access has been suspended by admin.From now you can access your app and panel again Please contact us for any queries we’re always happy to help.</div><div><br></div><div>Meanwhile, click here to visit theNichen shop website</div><div><font color=\"#0000ff\"> <a href=\"https://nichen.net\" target=\"_blank\">https://nichen.net</a></font></div>', NULL, NULL, NULL, NULL, NULL, 'Please contact us for any queries, we’re always happy to help.', 'Copyright 2024 Nichen. All right reserved.', NULL, NULL, '[\"product_information\",\"order_information\",\"button_content\",\"banner_image\"]', 1, 1, 1, 1, '2024-08-08 03:41:30', '2024-08-08 03:41:30'),
(15, 'account-activation', 'vendor', 'account-activation', 'Account Activation', '<div><b>Hi {vendorName},</b></div><div><b><br></b></div><div>Your account suspension has been revoked by admin. From now you can access your app and panel again Please contact us for any queries we’re always happy to help.</div><div><br></div><div>Meanwhile, click here to visit theNichen shop website</div><div><font color=\"#0000ff\"> <a href=\"https://nichen.net\" target=\"_blank\">https://nichen.net</a></font></div>', NULL, NULL, NULL, NULL, NULL, 'Please contact us for any queries, we’re always happy to help.', 'Copyright 2024 Nichen. All right reserved.', NULL, NULL, '[\"product_information\",\"order_information\",\"button_content\",\"banner_image\"]', 1, 1, 1, 1, '2024-08-08 03:41:30', '2024-08-08 03:41:30'),
(16, 'forgot-password', 'vendor', 'forgot-password', 'Change Password Request', '<p><b>Hi {vendorName},</b></p><p>Please click the link below to change your password.</p>', NULL, NULL, NULL, NULL, NULL, 'Please contact us for any queries, we’re always happy to help.', 'Copyright 2024 Nichen. All right reserved.', NULL, NULL, '[\"product_information\",\"order_information\",\"button_content\",\"banner_image\"]', 1, 1, 1, 1, '2024-08-08 03:41:30', '2024-08-08 03:41:30'),
(17, 'order-received', 'vendor', 'order-received', 'New Order Received', '<p><b>Hi {vendorName},</b></p><p>We have sent you this email to notify that you have a new order.You will be able to see your orders after login to your panel.</p>', NULL, NULL, NULL, NULL, NULL, 'Please contact us for any queries, we’re always happy to help.', 'Copyright 2024 Nichen. All right reserved.', NULL, NULL, '[\"icon\",\"product_information\",\"button_content\",\"banner_image\"]', 1, 1, 1, 1, '2024-08-08 03:41:30', '2024-08-08 03:41:30'),
(18, 'reset-password-verification', 'delivery-man', 'reset-password-verification', 'OTP Verification For Password Reset', '<p><b>Hi {deliveryManName},</b></p><p>Your verification code is</p>', NULL, NULL, NULL, NULL, NULL, 'Please contact us for any queries, we’re always happy to help.', 'Copyright 2024 Nichen. All right reserved.', NULL, NULL, '[\"product_information\",\"order_information\",\"button_content\",\"banner_image\"]', 1, 1, 1, 1, '2024-08-08 03:41:30', '2024-08-08 03:41:30');

-- --------------------------------------------------------

--
-- Structure de la table `emergency_contacts`
--

CREATE TABLE `emergency_contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `name` varchar(191) NOT NULL,
  `country_code` varchar(20) DEFAULT NULL,
  `phone` varchar(25) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `error_logs`
--

CREATE TABLE `error_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status_code` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `hit_counts` int(11) NOT NULL DEFAULT 0,
  `redirect_url` varchar(255) DEFAULT NULL,
  `redirect_status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `error_logs`
--

INSERT INTO `error_logs` (`id`, `status_code`, `url`, `hit_counts`, `redirect_url`, `redirect_status`, `created_at`, `updated_at`) VALUES
(1, 404, 'https://nichen.net/admin/brand/dummy', 1, NULL, NULL, '2024-08-08 03:46:57', '2024-08-08 03:46:57'),
(2, 404, 'https://nichen.net/seller/auth/login', 3, NULL, NULL, '2024-08-08 00:31:13', '2024-08-10 08:10:26'),
(3, 404, 'https://nichen.net/apple-touch-icon-120x120-precomposed.png', 3, NULL, NULL, '2024-08-08 01:52:08', '2024-08-12 15:54:14'),
(4, 404, 'https://nichen.net/apple-touch-icon-120x120.png', 3, NULL, NULL, '2024-08-08 01:52:09', '2024-08-12 15:54:15'),
(5, 404, 'https://nichen.net/apple-touch-icon-precomposed.png', 3, NULL, NULL, '2024-08-08 01:52:10', '2024-08-12 15:54:17'),
(6, 404, 'https://nichen.net/apple-touch-icon.png', 3, NULL, NULL, '2024-08-08 01:52:11', '2024-08-12 15:54:18'),
(7, 404, 'https://nichen.net/firebase-messaging-sw.js', 160, NULL, NULL, '2024-08-08 08:44:06', '2024-08-16 22:06:03'),
(8, 404, 'https://nichen.net/sm/26388f9cb48964f9a2b30e7cb67cc02f79293bac95b62ec2f74c2b606a725ea1.map', 7, NULL, NULL, '2024-08-08 11:02:13', '2024-08-12 02:18:30'),
(9, 404, 'https://nichen.net/facebook.com', 1, NULL, NULL, '2024-08-08 14:16:50', '2024-08-08 14:16:50'),
(10, 404, 'https://nichen.net/javascript', 1, NULL, NULL, '2024-08-08 14:16:54', '2024-08-08 14:16:54'),
(11, 404, 'https://nichen.net/window.location.href', 1, NULL, NULL, '2024-08-08 14:17:02', '2024-08-08 14:17:02'),
(12, 404, 'https://nichen.net/customer/auth/code/captcha', 1, NULL, NULL, '2024-08-08 14:17:03', '2024-08-08 14:17:03'),
(13, 404, 'https://nichen.net/contact/code/captcha', 1, NULL, NULL, '2024-08-08 14:17:31', '2024-08-08 14:17:31'),
(14, 404, 'https://nichen.net/customer/auth/facebook.com', 1, NULL, NULL, '2024-08-08 14:17:42', '2024-08-08 14:17:42'),
(15, 404, 'https://nichen.net/customer/auth/javascript', 1, NULL, NULL, '2024-08-08 14:17:42', '2024-08-08 14:17:42'),
(16, 404, 'https://nichen.net/customer/auth/window.location.href', 1, NULL, NULL, '2024-08-08 14:17:44', '2024-08-08 14:17:44'),
(17, 404, 'https://nichen.net/${l}', 1, NULL, NULL, '2024-08-08 14:17:44', '2024-08-08 14:17:44'),
(18, 404, 'https://nichen.net/e', 1, NULL, NULL, '2024-08-08 14:17:44', '2024-08-08 14:17:44'),
(19, 404, 'https://nichen.net/t.params.url', 1, NULL, NULL, '2024-08-08 14:17:45', '2024-08-08 14:17:45'),
(20, 404, 'https://nichen.net/img.svg', 1, NULL, NULL, '2024-08-08 14:17:45', '2024-08-08 14:17:45'),
(21, 404, 'https://nichen.net/shop/apply?fbclid=PAZXh0bgNhZW0CMTEAAaY6vHPjTXpWKEpzioCQ9Z7E0jl6-Z8KCIgLR2xBFuEYek44ZzdPoOW1ZQw_aem_IkkhgK4B9CQPsLQJaY_rSQ', 1, NULL, NULL, '2024-08-08 21:17:16', '2024-08-08 21:17:16'),
(22, 404, 'https://nichen.net/admin/products/dummy', 4, NULL, NULL, '2024-08-09 15:13:19', '2024-08-15 15:54:00'),
(23, 404, 'https://nichen.net/public/assets/back-end/img/icons/product-upload-icon.svg-dummy', 5, NULL, NULL, '2024-08-09 15:13:19', '2024-08-15 15:54:00'),
(24, 404, 'https://nichen.net/admin/products/img', 10, NULL, NULL, '2024-08-09 15:14:27', '2024-08-15 16:01:21'),
(25, 404, 'https://nichen.net/auth/login', 1, NULL, NULL, '2024-08-09 15:18:24', '2024-08-09 15:18:24'),
(26, 404, 'https://nichen.net/admin/login', 7, NULL, NULL, '2024-08-09 15:18:36', '2024-08-11 21:37:07'),
(27, 404, 'https://nichen.net/user/login', 1, NULL, NULL, '2024-08-09 15:18:39', '2024-08-09 15:18:39'),
(28, 404, 'https://nichen.net/admin', 3, NULL, NULL, '2024-08-09 15:18:59', '2024-08-15 15:05:59'),
(29, 404, 'https://nichen.net/shop/apply?fbclid=PAAaZYcpeu9xrah5vyyr-swA_VUsfaBJToBUXUIFvqX34q3SsFdEI7W9QOnWE', 1, NULL, NULL, '2024-08-09 20:11:18', '2024-08-09 20:11:18'),
(30, 404, 'https://nichen.net/shop/apply?fbclid=PAZXh0bgNhZW0CMTEAAaY0B2bj7bqHm8yHbGdJ5QLErOUWreAJN6bdy4h9UoMRRK-1mMfr3yYHtA8_aem_xfXvdT5WL9xvtC4JzIkM7g', 1, NULL, NULL, '2024-08-10 21:06:38', '2024-08-10 21:06:38'),
(31, 404, 'https://nichen.net/seller/auth/code/captcha/1?captcha_session_id=default_recaptcha_id_seller_login', 3, NULL, NULL, '2024-08-10 23:54:08', '2024-08-14 17:44:31'),
(32, 404, 'https://nichen.net/public/assets/front-end/png/handshake.png', 3, NULL, NULL, '2024-08-10 23:54:09', '2024-08-14 17:44:31'),
(33, 404, 'https://nichen.net/shop/apply?fbclid=PAZXh0bgNhZW0CMTEAAaZam6l7eWe8VAaFMTanaX0l1djUShw_6IldmIP4meeH-kqHLOFHeJbnoCE_aem_C8SLx3A-J_dBlOSQfUqhrw', 1, NULL, NULL, '2024-08-11 11:12:29', '2024-08-11 11:12:29'),
(34, 404, 'https://nichen.net/admin/dashboard', 1, NULL, NULL, '2024-08-11 21:36:43', '2024-08-11 21:36:43'),
(35, 404, 'https://nichen.net/api/v4/products/latest', 1, NULL, NULL, '2024-08-11 22:02:18', '2024-08-11 22:02:18'),
(36, 404, 'https://nichen.net/api/v1/customer/actions/getTopShopsAndFavoriteShops', 1, NULL, NULL, '2024-08-11 22:06:46', '2024-08-11 22:06:46'),
(37, 404, 'https://nichen.net//api/v1/customer/actions/point-action?action=seen&function=add_points&storeId=1&userId=35', 1, NULL, NULL, '2024-08-11 22:15:10', '2024-08-11 22:15:10'),
(38, 404, 'https://nichen.net/api/v1/customer/actions/getTopShopsAndFavoriteShops-action', 3, NULL, NULL, '2024-08-11 22:17:21', '2024-08-11 22:17:25'),
(39, 404, 'https://nichen.net/api/v1/customer/actions/getTopShopsAndFavoriteShops-action?userId=35', 2, NULL, NULL, '2024-08-11 22:19:10', '2024-08-11 22:20:19'),
(40, 404, 'https://nichen.net/shop/apply?fbclid=PAZXh0bgNhZW0CMTEAAaakh5i2N-ooILPlHdDaE-OtuvkknODyhybh3GUZTRUTV7g05W_vHfQ3kHQ_aem_DhZ3zB-hGDlLwphTOGzWSA', 1, NULL, NULL, '2024-08-12 22:19:58', '2024-08-12 22:19:58'),
(41, 404, 'https://www.nichen.net/firebase-messaging-sw.js', 3, NULL, NULL, '2024-08-13 22:04:34', '2024-08-13 22:04:53'),
(42, 404, 'https://nichen.net/shop/apply?fbclid=PAZXh0bgNhZW0CMTEAAaZCVmwzLFfpTlfzpPwNl2DMUp5uMOoNlwTTHKZufAoOYyyBUUsl1_OfA4U_aem_9mxzdAGIigXLmSBuG25vdg', 1, NULL, NULL, '2024-08-14 05:36:26', '2024-08-14 05:36:26'),
(43, 404, 'https://nichen.net/admin/banner', 1, NULL, NULL, '2024-08-15 13:09:12', '2024-08-15 13:09:12'),
(44, 404, 'https://nichen.net/assets/img/media/form-bg.png', 4, NULL, NULL, '2024-08-15 15:23:13', '2024-08-16 21:54:06'),
(45, 404, 'https://nichen.net/public/assets/back-end/img/system-setup.png', 1, NULL, NULL, '2024-08-15 15:34:45', '2024-08-15 15:34:45'),
(46, 404, 'https://nichen.net/admin/banner/list', 3, NULL, NULL, '2024-08-15 18:43:13', '2024-08-16 17:41:29'),
(47, 404, 'https://nichen.net/shop/apply?fbclid=PAZXh0bgNhZW0CMTEAAabRzPWaLqJEQ0y-AvQNH67eRdKdaBBHO1J8SRCM1YEgrkAq6j7JSU7lGWE_aem_jcdXOxcimMyMEpHf7CHB9w', 1, NULL, NULL, '2024-08-15 21:57:04', '2024-08-15 21:57:04'),
(48, 404, 'https://nichen.net/shop/apply?fbclid=PAY2xjawEsTR8BpvBQqBcD6qkb43hI44EhFdvJ1IxKFwBS-nn_A-o9yYInGpgbOUWAEQR5yg', 1, NULL, NULL, '2024-08-16 15:18:40', '2024-08-16 15:18:40'),
(49, 404, 'https://nichen.net/shop/apply:bena6346@gmail.com:12062004amine', 1, NULL, NULL, '2024-08-16 21:16:27', '2024-08-16 21:16:27'),
(50, 404, 'https://nichen.net/shop/apply', 1, NULL, NULL, '2024-08-16 21:16:34', '2024-08-16 21:16:34'),
(51, 404, 'https://nichen.net/admin/orders/get-order-data', 1, NULL, NULL, '2024-08-16 21:56:08', '2024-08-16 21:56:08'),
(52, 404, 'https://nichen.net/admin/messages/new-notification', 1, NULL, NULL, '2024-08-16 21:56:08', '2024-08-16 21:56:08'),
(53, 404, 'https://nichen.net/admin/vendors/list', 1, NULL, NULL, '2024-08-16 21:56:32', '2024-08-16 21:56:32');

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `feature_deals`
--

CREATE TABLE `feature_deals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `url` varchar(191) DEFAULT NULL,
  `photo` varchar(191) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `flash_deals`
--

CREATE TABLE `flash_deals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `background_color` varchar(255) DEFAULT NULL,
  `text_color` varchar(255) DEFAULT NULL,
  `banner` varchar(100) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `deal_type` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `flash_deal_products`
--

CREATE TABLE `flash_deal_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `flash_deal_id` bigint(20) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `discount_type` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `guest_users`
--

CREATE TABLE `guest_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `fcm_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `guest_users`
--

INSERT INTO `guest_users` (`id`, `ip_address`, `fcm_token`, `created_at`, `updated_at`) VALUES
(1, '::1', NULL, '2024-02-19 08:35:50', NULL),
(2, '::1', NULL, '2024-03-27 03:10:49', NULL),
(3, '::1', NULL, '2024-03-27 03:12:35', NULL),
(4, '::1', NULL, '2024-05-18 10:57:05', NULL),
(5, '41.108.179.13', NULL, '2024-08-08 03:41:43', '2024-08-08 03:41:43'),
(6, '154.121.28.159', NULL, '2024-08-08 03:42:28', '2024-08-08 03:42:28'),
(7, '105.235.131.102', NULL, '2024-08-07 23:51:16', '2024-08-07 23:51:16'),
(8, '105.110.151.181', NULL, '2024-08-08 00:26:22', '2024-08-08 00:26:22'),
(9, '154.251.164.34', NULL, '2024-08-08 00:28:26', '2024-08-08 00:28:26'),
(10, '197.207.74.244', NULL, '2024-08-08 00:30:52', '2024-08-08 00:30:52'),
(11, '105.103.167.232', NULL, '2024-08-08 00:31:25', '2024-08-08 00:31:25'),
(12, '154.251.164.34', NULL, '2024-08-08 01:34:38', '2024-08-08 01:34:38'),
(13, '105.103.167.232', NULL, '2024-08-08 01:47:45', '2024-08-08 01:47:45'),
(14, '105.111.44.171', NULL, '2024-08-08 01:52:07', '2024-08-08 01:52:07'),
(15, '105.103.167.232', NULL, '2024-08-08 02:22:18', '2024-08-08 02:22:18'),
(16, '129.45.112.175', NULL, '2024-08-08 08:44:00', '2024-08-08 08:44:00'),
(17, '129.45.112.175', NULL, '2024-08-08 08:51:13', '2024-08-08 08:51:13'),
(18, '154.241.129.40', NULL, '2024-08-08 09:58:17', '2024-08-08 09:58:17'),
(19, '129.45.122.3', NULL, '2024-08-08 10:12:42', '2024-08-08 10:12:42'),
(20, '129.45.122.3', NULL, '2024-08-08 10:12:42', '2024-08-08 10:12:42'),
(21, '105.235.132.191', NULL, '2024-08-08 10:43:06', '2024-08-08 10:43:06'),
(22, '129.45.112.175', NULL, '2024-08-08 11:01:52', '2024-08-08 11:01:52'),
(23, '105.96.222.173', NULL, '2024-08-08 11:08:15', '2024-08-08 11:08:15'),
(24, '129.45.125.195', NULL, '2024-08-08 11:15:06', '2024-08-08 11:15:06'),
(25, '41.101.23.37', NULL, '2024-08-08 11:18:37', '2024-08-08 11:18:37'),
(26, '129.45.92.215', NULL, '2024-08-08 11:48:56', '2024-08-08 11:48:56'),
(27, '154.121.71.2', NULL, '2024-08-08 12:43:16', '2024-08-08 12:43:16'),
(28, '129.45.112.175', NULL, '2024-08-08 12:44:09', '2024-08-08 12:44:09'),
(29, '105.235.134.67', NULL, '2024-08-08 13:14:39', '2024-08-08 13:14:39'),
(30, '2001:4860:7:201::fc', NULL, '2024-08-08 13:22:21', '2024-08-08 13:22:21'),
(31, '105.103.141.238', NULL, '2024-08-08 13:22:23', '2024-08-08 13:22:23'),
(32, '129.45.112.175', NULL, '2024-08-08 14:16:25', '2024-08-08 14:16:25'),
(33, '129.45.112.175', NULL, '2024-08-08 14:16:40', '2024-08-08 14:16:40'),
(34, '105.102.94.43', NULL, '2024-08-08 16:30:11', '2024-08-08 16:30:11'),
(35, '2001:4860:7:601::fc', NULL, '2024-08-08 16:53:41', '2024-08-08 16:53:41'),
(36, '2001:4860:7:201::fc', NULL, '2024-08-08 16:53:45', '2024-08-08 16:53:45'),
(37, '41.200.217.144', NULL, '2024-08-08 17:02:30', '2024-08-08 17:02:30'),
(38, '41.200.217.144', NULL, '2024-08-08 17:02:31', '2024-08-08 17:02:31'),
(39, '41.200.217.144', NULL, '2024-08-08 17:02:33', '2024-08-08 17:02:33'),
(40, '41.200.217.144', NULL, '2024-08-08 17:02:33', '2024-08-08 17:02:33'),
(41, '41.200.217.144', NULL, '2024-08-08 17:02:37', '2024-08-08 17:02:37'),
(42, '41.200.217.144', NULL, '2024-08-08 17:02:49', '2024-08-08 17:02:49'),
(43, '94.203.243.35', NULL, '2024-08-08 18:02:56', '2024-08-08 18:02:56'),
(44, '105.97.97.139', NULL, '2024-08-08 20:03:57', '2024-08-08 20:03:57'),
(45, '105.99.246.43', NULL, '2024-08-08 20:45:13', '2024-08-08 20:45:13'),
(46, '2001:4860:7:201::fd', NULL, '2024-08-08 20:51:11', '2024-08-08 20:51:11'),
(47, '129.45.2.109', NULL, '2024-08-08 21:07:06', '2024-08-08 21:07:06'),
(48, '129.45.99.158', NULL, '2024-08-08 21:21:02', '2024-08-08 21:21:02'),
(49, '154.251.72.217', NULL, '2024-08-08 22:38:21', '2024-08-08 22:38:21'),
(50, '105.235.139.138', NULL, '2024-08-08 23:00:22', '2024-08-08 23:00:22'),
(51, '105.235.139.138', NULL, '2024-08-08 23:00:55', '2024-08-08 23:00:55'),
(52, '105.235.139.138', NULL, '2024-08-08 23:01:15', '2024-08-08 23:01:15'),
(53, '197.207.102.39', NULL, '2024-08-09 01:55:32', '2024-08-09 01:55:32'),
(54, '105.98.242.25', NULL, '2024-08-09 09:01:57', '2024-08-09 09:01:57'),
(55, '129.45.99.158', NULL, '2024-08-09 09:27:12', '2024-08-09 09:27:12'),
(56, '197.200.231.169', NULL, '2024-08-09 10:40:43', '2024-08-09 10:40:43'),
(57, '197.200.231.169', NULL, '2024-08-09 10:41:12', '2024-08-09 10:41:12'),
(58, '105.110.130.17', NULL, '2024-08-09 10:54:02', '2024-08-09 10:54:02'),
(59, '105.110.130.17', NULL, '2024-08-09 10:54:44', '2024-08-09 10:54:44'),
(60, '41.96.187.225', NULL, '2024-08-09 12:14:46', '2024-08-09 12:14:46'),
(61, '105.102.155.29', NULL, '2024-08-09 12:53:20', '2024-08-09 12:53:20'),
(62, '41.108.141.195', NULL, '2024-08-09 14:03:59', '2024-08-09 14:03:59'),
(63, '129.45.54.8', NULL, '2024-08-09 14:57:36', '2024-08-09 14:57:36'),
(64, '129.45.89.119', NULL, '2024-08-09 15:02:51', '2024-08-09 15:02:51'),
(65, '129.45.126.236', NULL, '2024-08-09 15:16:22', '2024-08-09 15:16:22'),
(66, '105.235.128.4', NULL, '2024-08-09 16:27:15', '2024-08-09 16:27:15'),
(67, '129.45.90.176', NULL, '2024-08-09 18:34:31', '2024-08-09 18:34:31'),
(68, '154.121.83.255', NULL, '2024-08-09 22:08:52', '2024-08-09 22:08:52'),
(69, '129.45.81.40', NULL, '2024-08-09 22:30:27', '2024-08-09 22:30:27'),
(70, '41.97.109.221', NULL, '2024-08-09 22:44:19', '2024-08-09 22:44:19'),
(71, '129.45.91.27', NULL, '2024-08-09 22:49:45', '2024-08-09 22:49:45'),
(72, '41.104.142.211', NULL, '2024-08-09 23:01:17', '2024-08-09 23:01:17'),
(73, '154.247.10.241', NULL, '2024-08-10 00:35:26', '2024-08-10 00:35:26'),
(74, '154.121.91.244', NULL, '2024-08-10 07:47:30', '2024-08-10 07:47:30'),
(75, '129.45.55.135', NULL, '2024-08-10 10:02:23', '2024-08-10 10:02:23'),
(76, '129.45.126.74', NULL, '2024-08-10 13:36:27', '2024-08-10 13:36:27'),
(77, '197.204.227.240', NULL, '2024-08-10 13:53:39', '2024-08-10 13:53:39'),
(78, '105.97.177.252', NULL, '2024-08-10 14:00:39', '2024-08-10 14:00:39'),
(79, '105.103.187.149', NULL, '2024-08-10 14:29:48', '2024-08-10 14:29:48'),
(80, '129.45.126.74', NULL, '2024-08-10 17:04:01', '2024-08-10 17:04:01'),
(81, '105.235.136.224', NULL, '2024-08-10 19:34:18', '2024-08-10 19:34:18'),
(82, '197.200.234.182', NULL, '2024-08-10 21:00:25', '2024-08-10 21:00:25'),
(83, '154.255.202.193', NULL, '2024-08-10 22:35:32', '2024-08-10 22:35:32'),
(84, '105.110.182.144', NULL, '2024-08-11 00:41:52', '2024-08-11 00:41:52'),
(85, '129.45.122.193', NULL, '2024-08-11 00:42:35', '2024-08-11 00:42:35'),
(86, '105.104.78.132', NULL, '2024-08-11 00:58:23', '2024-08-11 00:58:23'),
(87, '154.121.15.210', NULL, '2024-08-11 01:32:45', '2024-08-11 01:32:45'),
(88, '2a09:bac1:5fa0::31:db', NULL, '2024-08-11 08:10:24', '2024-08-11 08:10:24'),
(89, '41.103.216.224', NULL, '2024-08-11 10:15:03', '2024-08-11 10:15:03'),
(90, '129.45.127.174', NULL, '2024-08-11 10:24:17', '2024-08-11 10:24:17'),
(91, '197.117.119.81', NULL, '2024-08-11 10:58:57', '2024-08-11 10:58:57'),
(92, '197.205.124.80', NULL, '2024-08-11 11:07:16', '2024-08-11 11:07:16'),
(93, '154.121.106.197', NULL, '2024-08-11 11:19:45', '2024-08-11 11:19:45'),
(94, '197.116.205.97', NULL, '2024-08-11 11:46:11', '2024-08-11 11:46:11'),
(95, '105.99.18.71', NULL, '2024-08-11 12:06:35', '2024-08-11 12:06:35'),
(96, '105.235.128.42', NULL, '2024-08-11 12:24:33', '2024-08-11 12:24:33'),
(97, '105.96.36.54', NULL, '2024-08-11 14:12:01', '2024-08-11 14:12:01'),
(98, '154.121.89.168', NULL, '2024-08-11 14:55:24', '2024-08-11 14:55:24'),
(99, '129.45.45.210', NULL, '2024-08-11 14:56:28', '2024-08-11 14:56:28'),
(100, '105.108.184.80', NULL, '2024-08-11 15:35:57', '2024-08-11 15:35:57'),
(101, '105.108.184.80', NULL, '2024-08-11 15:38:01', '2024-08-11 15:38:01'),
(102, '41.109.144.147', NULL, '2024-08-11 15:46:45', '2024-08-11 15:46:45'),
(103, '105.105.60.174', NULL, '2024-08-11 17:18:10', '2024-08-11 17:18:10'),
(104, '105.108.30.117', NULL, '2024-08-11 18:46:47', '2024-08-11 18:46:47'),
(105, '154.251.90.150', NULL, '2024-08-11 19:45:57', '2024-08-11 19:45:57'),
(106, '154.121.18.27', NULL, '2024-08-11 20:26:43', '2024-08-11 20:26:43'),
(107, '197.206.182.97', NULL, '2024-08-11 21:27:38', '2024-08-11 21:27:38'),
(108, '41.99.133.199', NULL, '2024-08-11 21:31:00', '2024-08-11 21:31:00'),
(109, '41.99.133.199', NULL, '2024-08-11 21:31:30', '2024-08-11 21:31:30'),
(110, '105.103.115.99', NULL, '2024-08-11 21:59:30', '2024-08-11 21:59:30'),
(111, '41.105.206.84', NULL, '2024-08-11 22:01:06', '2024-08-11 22:01:06'),
(112, '41.105.206.84', NULL, '2024-08-11 22:02:27', '2024-08-11 22:02:27'),
(113, '41.104.219.128', NULL, '2024-08-11 23:48:40', '2024-08-11 23:48:40'),
(114, '129.45.91.117', NULL, '2024-08-12 01:41:22', '2024-08-12 01:41:22'),
(115, '129.45.91.117', NULL, '2024-08-12 02:18:17', '2024-08-12 02:18:17'),
(116, '65.38.92.162', NULL, '2024-08-12 03:52:07', '2024-08-12 03:52:07'),
(117, '105.104.80.40', NULL, '2024-08-12 03:57:14', '2024-08-12 03:57:14'),
(118, '41.98.126.185', NULL, '2024-08-12 09:44:50', '2024-08-12 09:44:50'),
(119, '2a04:cec0:11a9:c8d4:d454:5e04:9827:5f7c', NULL, '2024-08-12 10:09:04', '2024-08-12 10:09:04'),
(120, '82.145.212.188', NULL, '2024-08-12 12:38:49', '2024-08-12 12:38:49'),
(121, '41.97.114.189', NULL, '2024-08-12 13:00:01', '2024-08-12 13:00:01'),
(122, '154.121.80.45', NULL, '2024-08-12 13:02:12', '2024-08-12 13:02:12'),
(123, '105.235.136.189', NULL, '2024-08-12 13:21:44', '2024-08-12 13:21:44'),
(124, '129.45.43.44', NULL, '2024-08-12 13:32:59', '2024-08-12 13:32:59'),
(125, '154.121.81.120', NULL, '2024-08-12 14:03:14', '2024-08-12 14:03:14'),
(126, '41.105.237.95', NULL, '2024-08-12 14:12:18', '2024-08-12 14:12:18'),
(127, '105.99.37.59', NULL, '2024-08-12 15:54:12', '2024-08-12 15:54:12'),
(128, '41.109.52.143', NULL, '2024-08-12 22:15:01', '2024-08-12 22:15:01'),
(129, '154.121.20.201', NULL, '2024-08-12 23:17:20', '2024-08-12 23:17:20'),
(130, '154.121.20.201', NULL, '2024-08-12 23:18:13', '2024-08-12 23:18:13'),
(131, '105.235.135.207', NULL, '2024-08-12 23:19:44', '2024-08-12 23:19:44'),
(132, '105.109.241.171', NULL, '2024-08-13 04:20:13', '2024-08-13 04:20:13'),
(133, '129.45.125.112', NULL, '2024-08-13 04:46:50', '2024-08-13 04:46:50'),
(134, '129.45.112.136', NULL, '2024-08-13 09:30:06', '2024-08-13 09:30:06'),
(135, '41.102.24.30', NULL, '2024-08-13 10:18:06', '2024-08-13 10:18:06'),
(136, '41.201.236.103', NULL, '2024-08-13 13:30:51', '2024-08-13 13:30:51'),
(137, '105.235.138.202', NULL, '2024-08-13 15:04:22', '2024-08-13 15:04:22'),
(138, '105.235.138.202', NULL, '2024-08-13 15:05:04', '2024-08-13 15:05:04'),
(139, '105.101.34.69', NULL, '2024-08-13 15:20:59', '2024-08-13 15:20:59'),
(140, '172.105.87.180', NULL, '2024-08-13 15:55:20', '2024-08-13 15:55:20'),
(141, '172.105.87.180', NULL, '2024-08-13 15:56:24', '2024-08-13 15:56:24'),
(142, '77.208.170.7', NULL, '2024-08-13 17:03:10', '2024-08-13 17:03:10'),
(143, '129.45.112.136', NULL, '2024-08-13 17:21:44', '2024-08-13 17:21:44'),
(144, '129.45.3.126', NULL, '2024-08-13 18:49:25', '2024-08-13 18:49:25'),
(145, '129.45.41.239', NULL, '2024-08-13 19:15:35', '2024-08-13 19:15:35'),
(146, '197.207.3.200', NULL, '2024-08-13 19:23:34', '2024-08-13 19:23:34'),
(147, '129.45.116.85', NULL, '2024-08-13 20:51:31', '2024-08-13 20:51:31'),
(148, '154.251.31.213', NULL, '2024-08-13 21:12:10', '2024-08-13 21:12:10'),
(149, '154.251.31.213', NULL, '2024-08-13 21:12:18', '2024-08-13 21:12:18'),
(150, '41.101.250.11', NULL, '2024-08-13 22:04:27', '2024-08-13 22:04:27'),
(151, '154.247.15.211', NULL, '2024-08-14 00:07:16', '2024-08-14 00:07:16'),
(152, '105.101.41.75', NULL, '2024-08-14 01:15:14', '2024-08-14 01:15:14'),
(153, '197.204.34.118', NULL, '2024-08-14 01:56:10', '2024-08-14 01:56:10'),
(154, '41.109.247.183', NULL, '2024-08-14 01:57:44', '2024-08-14 01:57:44'),
(155, '154.247.59.131', NULL, '2024-08-14 03:46:22', '2024-08-14 03:46:22'),
(156, '102.212.139.248', NULL, '2024-08-14 07:51:42', '2024-08-14 07:51:42'),
(157, '123.160.221.131', NULL, '2024-08-14 08:09:05', '2024-08-14 08:09:05'),
(158, '123.160.221.131', NULL, '2024-08-14 08:09:07', '2024-08-14 08:09:07'),
(159, '154.121.38.224', NULL, '2024-08-14 08:47:32', '2024-08-14 08:47:32'),
(160, '154.121.38.224', NULL, '2024-08-14 08:47:41', '2024-08-14 08:47:41'),
(161, '129.45.116.85', NULL, '2024-08-14 08:54:38', '2024-08-14 08:54:38'),
(162, '41.102.160.19', NULL, '2024-08-14 10:19:25', '2024-08-14 10:19:25'),
(163, '129.45.116.85', NULL, '2024-08-14 16:43:59', '2024-08-14 16:43:59'),
(164, '154.121.39.113', NULL, '2024-08-14 17:19:08', '2024-08-14 17:19:08'),
(165, '129.45.2.74', NULL, '2024-08-14 17:28:42', '2024-08-14 17:28:42'),
(166, '154.121.59.89', NULL, '2024-08-14 18:04:48', '2024-08-14 18:04:48'),
(167, '105.102.23.30', NULL, '2024-08-14 18:09:58', '2024-08-14 18:09:58'),
(168, '197.207.131.188', NULL, '2024-08-14 19:03:37', '2024-08-14 19:03:37'),
(169, '2a02:810b:433f:fac0:b075:33f:bcef:2d56', NULL, '2024-08-14 19:29:25', '2024-08-14 19:29:25'),
(170, '129.45.117.189', NULL, '2024-08-14 20:15:22', '2024-08-14 20:15:22'),
(171, '105.235.139.51', NULL, '2024-08-14 20:54:46', '2024-08-14 20:54:46'),
(172, '105.101.144.234', NULL, '2024-08-14 22:59:35', '2024-08-14 22:59:35'),
(173, '154.241.83.148', NULL, '2024-08-14 23:00:45', '2024-08-14 23:00:45'),
(174, '197.207.124.188', NULL, '2024-08-15 00:46:39', '2024-08-15 00:46:39'),
(175, '129.45.116.85', NULL, '2024-08-15 10:34:32', '2024-08-15 10:34:32'),
(176, '41.109.206.254', NULL, '2024-08-15 13:04:52', '2024-08-15 13:04:52'),
(177, '129.45.17.86', NULL, '2024-08-15 13:46:10', '2024-08-15 13:46:10'),
(178, '41.105.220.241', NULL, '2024-08-15 14:54:20', '2024-08-15 14:54:20'),
(179, '41.104.117.220', NULL, '2024-08-15 15:22:09', '2024-08-15 15:22:09'),
(180, '105.110.177.48', NULL, '2024-08-15 16:35:22', '2024-08-15 16:35:22'),
(181, '41.105.220.241', NULL, '2024-08-15 18:21:17', '2024-08-15 18:21:17'),
(182, '105.235.132.169', NULL, '2024-08-15 18:21:29', '2024-08-15 18:21:29'),
(183, '105.98.219.146', NULL, '2024-08-15 18:42:33', '2024-08-15 18:42:33'),
(184, '129.45.41.85', NULL, '2024-08-15 18:44:35', '2024-08-15 18:44:35'),
(185, '129.45.41.85', NULL, '2024-08-15 19:21:10', '2024-08-15 19:21:10'),
(186, '197.202.169.121', NULL, '2024-08-15 19:59:38', '2024-08-15 19:59:38'),
(187, '105.235.136.221', NULL, '2024-08-15 22:37:28', '2024-08-15 22:37:28'),
(188, '105.235.134.232', NULL, '2024-08-15 22:51:56', '2024-08-15 22:51:56'),
(189, '129.45.17.86', NULL, '2024-08-16 00:21:23', '2024-08-16 00:21:23'),
(190, '154.247.234.76', NULL, '2024-08-16 09:24:31', '2024-08-16 09:24:31'),
(191, '105.235.132.211', NULL, '2024-08-16 10:25:26', '2024-08-16 10:25:26'),
(192, '129.45.119.37', NULL, '2024-08-16 11:17:22', '2024-08-16 11:17:22'),
(193, '154.245.44.57', NULL, '2024-08-16 12:22:40', '2024-08-16 12:22:40'),
(194, '129.45.93.100', NULL, '2024-08-16 13:41:11', '2024-08-16 13:41:11'),
(195, '197.204.80.107', NULL, '2024-08-16 14:12:00', '2024-08-16 14:12:00'),
(196, '105.235.139.215', NULL, '2024-08-16 14:58:07', '2024-08-16 14:58:07'),
(197, '154.246.201.0', NULL, '2024-08-16 17:40:44', '2024-08-16 17:40:44'),
(198, '154.246.201.0', NULL, '2024-08-16 17:41:13', '2024-08-16 17:41:13'),
(199, '129.45.98.94', NULL, '2024-08-16 18:17:43', '2024-08-16 18:17:43'),
(200, '105.235.138.199', NULL, '2024-08-16 18:30:13', '2024-08-16 18:30:13'),
(201, '105.235.130.15', NULL, '2024-08-16 18:58:40', '2024-08-16 18:58:40'),
(202, '129.45.19.11', NULL, '2024-08-16 19:25:29', '2024-08-16 19:25:29'),
(203, '154.121.88.111', NULL, '2024-08-16 19:53:03', '2024-08-16 19:53:03'),
(204, '129.45.119.37', NULL, '2024-08-16 20:46:58', '2024-08-16 20:46:58'),
(205, '2003:cd:af0d:c122:3ca5:bc31:c5fa:21', NULL, '2024-08-16 21:16:40', '2024-08-16 21:16:40'),
(206, '105.103.97.88', NULL, '2024-08-16 21:49:42', '2024-08-16 21:49:42'),
(207, '105.103.97.88', NULL, '2024-08-16 21:54:02', '2024-08-16 21:54:02'),
(208, '129.45.119.37', NULL, '2024-08-16 22:05:44', '2024-08-16 22:05:44');

-- --------------------------------------------------------

--
-- Structure de la table `help_topics`
--

CREATE TABLE `help_topics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(191) NOT NULL DEFAULT 'default',
  `question` text DEFAULT NULL,
  `answer` text DEFAULT NULL,
  `ranking` int(11) NOT NULL DEFAULT 1,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `help_topics`
--

INSERT INTO `help_topics` (`id`, `type`, `question`, `answer`, `ranking`, `status`, `created_at`, `updated_at`) VALUES
(1, 'vendor_registration', 'How do I register as a seller?', 'To register, click on the \"Sign Up\" button, fill in your details, and verify your account via email.', 1, 1, NULL, NULL),
(2, 'vendor_registration', 'What are the fees for selling?', 'Our platform charges a small commission on each sale. There are no upfront listing fees.', 2, 1, NULL, NULL),
(3, 'vendor_registration', 'How do I upload products?', 'Log in to your seller account, go to the \"Upload Products\" section, and fill in the product details and images.', 3, 1, NULL, NULL),
(4, 'vendor_registration', 'How do I handle customer inquiries?', 'You can manage customer inquiries directly through our platform\'s messaging system, ensuring quick and efficient communication.', 4, 1, NULL, NULL),
(5, 'vendor_registration', 'How do I register as a seller?', 'To register, click on the \"Sign Up\" button, fill in your details, and verify your account via email.', 1, 1, NULL, NULL),
(6, 'vendor_registration', 'What are the fees for selling?', 'Our platform charges a small commission on each sale. There are no upfront listing fees.', 2, 1, NULL, NULL),
(7, 'vendor_registration', 'How do I upload products?', 'Log in to your seller account, go to the \"Upload Products\" section, and fill in the product details and images.', 3, 1, NULL, NULL),
(8, 'vendor_registration', 'How do I handle customer inquiries?', 'You can manage customer inquiries directly through our platform\'s messaging system, ensuring quick and efficient communication.', 4, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(191) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `loyalty_point_transactions`
--

CREATE TABLE `loyalty_point_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `transaction_id` char(36) NOT NULL,
  `credit` decimal(24,3) NOT NULL DEFAULT 0.000,
  `debit` decimal(24,3) NOT NULL DEFAULT 0.000,
  `balance` decimal(24,3) NOT NULL DEFAULT 0.000,
  `reference` varchar(191) DEFAULT NULL,
  `transaction_type` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2020_09_08_105159_create_admins_table', 1),
(5, '2020_09_08_111837_create_admin_roles_table', 1),
(6, '2020_09_16_142451_create_categories_table', 2),
(7, '2020_09_16_181753_create_categories_table', 3),
(8, '2020_09_17_134238_create_brands_table', 4),
(9, '2020_09_17_203054_create_attributes_table', 5),
(10, '2020_09_19_112509_create_coupons_table', 6),
(11, '2020_09_19_161802_create_curriencies_table', 7),
(12, '2020_09_20_114509_create_sellers_table', 8),
(13, '2020_09_23_113454_create_shops_table', 9),
(14, '2020_09_23_115615_create_shops_table', 10),
(15, '2020_09_23_153822_create_shops_table', 11),
(16, '2020_09_21_122817_create_products_table', 12),
(17, '2020_09_22_140800_create_colors_table', 12),
(18, '2020_09_28_175020_create_products_table', 13),
(19, '2020_09_28_180311_create_products_table', 14),
(20, '2020_10_04_105041_create_search_functions_table', 15),
(21, '2020_10_05_150730_create_customers_table', 15),
(22, '2020_10_08_133548_create_wishlists_table', 16),
(23, '2016_06_01_000001_create_oauth_auth_codes_table', 17),
(24, '2016_06_01_000002_create_oauth_access_tokens_table', 17),
(25, '2016_06_01_000003_create_oauth_refresh_tokens_table', 17),
(26, '2016_06_01_000004_create_oauth_clients_table', 17),
(27, '2016_06_01_000005_create_oauth_personal_access_clients_table', 17),
(28, '2020_10_06_133710_create_product_stocks_table', 17),
(29, '2020_10_06_134636_create_flash_deals_table', 17),
(30, '2020_10_06_134719_create_flash_deal_products_table', 17),
(31, '2020_10_08_115439_create_orders_table', 17),
(32, '2020_10_08_115453_create_order_details_table', 17),
(33, '2020_10_08_121135_create_shipping_addresses_table', 17),
(34, '2020_10_10_171722_create_business_settings_table', 17),
(35, '2020_09_19_161802_create_currencies_table', 18),
(36, '2020_10_12_152350_create_reviews_table', 18),
(37, '2020_10_12_161834_create_reviews_table', 19),
(38, '2020_10_12_180510_create_support_tickets_table', 20),
(39, '2020_10_14_140130_create_transactions_table', 21),
(40, '2020_10_14_143553_create_customer_wallets_table', 21),
(41, '2020_10_14_143607_create_customer_wallet_histories_table', 21),
(42, '2020_10_22_142212_create_support_ticket_convs_table', 21),
(43, '2020_10_24_234813_create_banners_table', 22),
(44, '2020_10_27_111557_create_shipping_methods_table', 23),
(45, '2020_10_27_114154_add_url_to_banners_table', 24),
(46, '2020_10_28_170308_add_shipping_id_to_order_details', 25),
(47, '2020_11_02_140528_add_discount_to_order_table', 26),
(48, '2020_11_03_162723_add_column_to_order_details', 27),
(49, '2020_11_08_202351_add_url_to_banners_table', 28),
(50, '2020_11_10_112713_create_help_topic', 29),
(51, '2020_11_10_141513_create_contacts_table', 29),
(52, '2020_11_15_180036_add_address_column_user_table', 30),
(53, '2020_11_18_170209_add_status_column_to_product_table', 31),
(54, '2020_11_19_115453_add_featured_status_product', 32),
(55, '2020_11_21_133302_create_deal_of_the_days_table', 33),
(56, '2020_11_20_172332_add_product_id_to_products', 34),
(57, '2020_11_27_234439_add__state_to_shipping_addresses', 34),
(58, '2020_11_28_091929_create_chattings_table', 35),
(59, '2020_12_02_011815_add_bank_info_to_sellers', 36),
(60, '2020_12_08_193234_create_social_medias_table', 37),
(61, '2020_12_13_122649_shop_id_to_chattings', 37),
(62, '2020_12_14_145116_create_seller_wallet_histories_table', 38),
(63, '2020_12_14_145127_create_seller_wallets_table', 38),
(64, '2020_12_15_174804_create_admin_wallets_table', 39),
(65, '2020_12_15_174821_create_admin_wallet_histories_table', 39),
(66, '2020_12_15_214312_create_feature_deals_table', 40),
(67, '2020_12_17_205712_create_withdraw_requests_table', 41),
(68, '2021_02_22_161510_create_notifications_table', 42),
(69, '2021_02_24_154706_add_deal_type_to_flash_deals', 43),
(70, '2021_03_03_204349_add_cm_firebase_token_to_users', 44),
(71, '2021_04_17_134848_add_column_to_order_details_stock', 45),
(72, '2021_05_12_155401_add_auth_token_seller', 46),
(73, '2021_06_03_104531_ex_rate_update', 47),
(74, '2021_06_03_222413_amount_withdraw_req', 48),
(75, '2021_06_04_154501_seller_wallet_withdraw_bal', 49),
(76, '2021_06_04_195853_product_dis_tax', 50),
(77, '2021_05_27_103505_create_product_translations_table', 51),
(78, '2021_06_17_054551_create_soft_credentials_table', 51),
(79, '2021_06_29_212549_add_active_col_user_table', 52),
(80, '2021_06_30_212619_add_col_to_contact', 53),
(81, '2021_07_01_160828_add_col_daily_needs_products', 54),
(82, '2021_07_04_182331_add_col_seller_sales_commission', 55),
(83, '2021_08_07_190655_add_seo_columns_to_products', 56),
(84, '2021_08_07_205913_add_col_to_category_table', 56),
(85, '2021_08_07_210808_add_col_to_shops_table', 56),
(86, '2021_08_14_205216_change_product_price_col_type', 56),
(87, '2021_08_16_201505_change_order_price_col', 56),
(88, '2021_08_16_201552_change_order_details_price_col', 56),
(89, '2019_09_29_154000_create_payment_cards_table', 57),
(90, '2021_08_17_213934_change_col_type_seller_earning_history', 57),
(91, '2021_08_17_214109_change_col_type_admin_earning_history', 57),
(92, '2021_08_17_214232_change_col_type_admin_wallet', 57),
(93, '2021_08_17_214405_change_col_type_seller_wallet', 57),
(94, '2021_08_22_184834_add_publish_to_products_table', 57),
(95, '2021_09_08_211832_add_social_column_to_users_table', 57),
(96, '2021_09_13_165535_add_col_to_user', 57),
(97, '2021_09_19_061647_add_limit_to_coupons_table', 57),
(98, '2021_09_20_020716_add_coupon_code_to_orders_table', 57),
(99, '2021_09_23_003059_add_gst_to_sellers_table', 57),
(100, '2021_09_28_025411_create_order_transactions_table', 57),
(101, '2021_10_02_185124_create_carts_table', 57),
(102, '2021_10_02_190207_create_cart_shippings_table', 57),
(103, '2021_10_03_194334_add_col_order_table', 57),
(104, '2021_10_03_200536_add_shipping_cost', 57),
(105, '2021_10_04_153201_add_col_to_order_table', 57),
(106, '2021_10_07_172701_add_col_cart_shop_info', 57),
(107, '2021_10_07_184442_create_phone_or_email_verifications_table', 57),
(108, '2021_10_07_185416_add_user_table_email_verified', 57),
(109, '2021_10_11_192739_add_transaction_amount_table', 57),
(110, '2021_10_11_200850_add_order_verification_code', 57),
(111, '2021_10_12_083241_add_col_to_order_transaction', 57),
(112, '2021_10_12_084440_add_seller_id_to_order', 57),
(113, '2021_10_12_102853_change_col_type', 57),
(114, '2021_10_12_110434_add_col_to_admin_wallet', 57),
(115, '2021_10_12_110829_add_col_to_seller_wallet', 57),
(116, '2021_10_13_091801_add_col_to_admin_wallets', 57),
(117, '2021_10_13_092000_add_col_to_seller_wallets_tax', 57),
(118, '2021_10_13_165947_rename_and_remove_col_seller_wallet', 57),
(119, '2021_10_13_170258_rename_and_remove_col_admin_wallet', 57),
(120, '2021_10_14_061603_column_update_order_transaction', 57),
(121, '2021_10_15_103339_remove_col_from_seller_wallet', 57),
(122, '2021_10_15_104419_add_id_col_order_tran', 57),
(123, '2021_10_15_213454_update_string_limit', 57),
(124, '2021_10_16_234037_change_col_type_translation', 57),
(125, '2021_10_16_234329_change_col_type_translation_1', 57),
(126, '2021_10_27_091250_add_shipping_address_in_order', 58),
(127, '2021_01_24_205114_create_paytabs_invoices_table', 59),
(128, '2021_11_20_043814_change_pass_reset_email_col', 59),
(129, '2021_11_25_043109_create_delivery_men_table', 60),
(130, '2021_11_25_062242_add_auth_token_delivery_man', 60),
(131, '2021_11_27_043405_add_deliveryman_in_order_table', 60),
(132, '2021_11_27_051432_create_delivery_histories_table', 60),
(133, '2021_11_27_051512_add_fcm_col_for_delivery_man', 60),
(134, '2021_12_15_123216_add_columns_to_banner', 60),
(135, '2022_01_04_100543_add_order_note_to_orders_table', 60),
(136, '2022_01_10_034952_add_lat_long_to_shipping_addresses_table', 60),
(137, '2022_01_10_045517_create_billing_addresses_table', 60),
(138, '2022_01_11_040755_add_is_billing_to_shipping_addresses_table', 60),
(139, '2022_01_11_053404_add_billing_to_orders_table', 60),
(140, '2022_01_11_234310_add_firebase_toke_to_sellers_table', 60),
(141, '2022_01_16_121801_change_colu_type', 60),
(142, '2022_01_22_101601_change_cart_col_type', 61),
(143, '2022_01_23_031359_add_column_to_orders_table', 61),
(144, '2022_01_28_235054_add_status_to_admins_table', 61),
(145, '2022_02_01_214654_add_pos_status_to_sellers_table', 61),
(146, '2019_12_14_000001_create_personal_access_tokens_table', 62),
(147, '2022_02_11_225355_add_checked_to_orders_table', 62),
(148, '2022_02_14_114359_create_refund_requests_table', 62),
(149, '2022_02_14_115757_add_refund_request_to_order_details_table', 62),
(150, '2022_02_15_092604_add_order_details_id_to_transactions_table', 62),
(151, '2022_02_15_121410_create_refund_transactions_table', 62),
(152, '2022_02_24_091236_add_multiple_column_to_refund_requests_table', 62),
(153, '2022_02_24_103827_create_refund_statuses_table', 62),
(154, '2022_03_01_121420_add_refund_id_to_refund_transactions_table', 62),
(155, '2022_03_10_091943_add_priority_to_categories_table', 63),
(156, '2022_03_13_111914_create_shipping_types_table', 63),
(157, '2022_03_13_121514_create_category_shipping_costs_table', 63),
(158, '2022_03_14_074413_add_four_column_to_products_table', 63),
(159, '2022_03_15_105838_add_shipping_to_carts_table', 63),
(160, '2022_03_16_070327_add_shipping_type_to_orders_table', 63),
(161, '2022_03_17_070200_add_delivery_info_to_orders_table', 63),
(162, '2022_03_18_143339_add_shipping_type_to_carts_table', 63),
(163, '2022_04_06_020313_create_subscriptions_table', 64),
(164, '2022_04_12_233704_change_column_to_products_table', 64),
(165, '2022_04_19_095926_create_jobs_table', 64),
(166, '2022_05_12_104247_create_wallet_transactions_table', 65),
(167, '2022_05_12_104511_add_two_column_to_users_table', 65),
(168, '2022_05_14_063309_create_loyalty_point_transactions_table', 65),
(169, '2022_05_26_044016_add_user_type_to_password_resets_table', 65),
(170, '2022_04_15_235820_add_provider', 66),
(171, '2022_07_21_101659_add_code_to_products_table', 66),
(172, '2022_07_26_103744_add_notification_count_to_notifications_table', 66),
(173, '2022_07_31_031541_add_minimum_order_qty_to_products_table', 66),
(174, '2022_08_11_172839_add_product_type_and_digital_product_type_and_digital_file_ready_to_products', 67),
(175, '2022_08_11_173941_add_product_type_and_digital_product_type_and_digital_file_to_order_details', 67),
(176, '2022_08_20_094225_add_product_type_and_digital_product_type_and_digital_file_ready_to_carts_table', 67),
(177, '2022_10_04_160234_add_banking_columns_to_delivery_men_table', 68),
(178, '2022_10_04_161339_create_deliveryman_wallets_table', 68),
(179, '2022_10_04_184506_add_deliverymanid_column_to_withdraw_requests_table', 68),
(180, '2022_10_11_103011_add_deliverymans_columns_to_chattings_table', 68),
(181, '2022_10_11_144902_add_deliverman_id_cloumn_to_reviews_table', 68),
(182, '2022_10_17_114744_create_order_status_histories_table', 68),
(183, '2022_10_17_120840_create_order_expected_delivery_histories_table', 68),
(184, '2022_10_18_084245_add_deliveryman_charge_and_expected_delivery_date', 68),
(185, '2022_10_18_130938_create_delivery_zip_codes_table', 68),
(186, '2022_10_18_130956_create_delivery_country_codes_table', 68),
(187, '2022_10_20_164712_create_delivery_man_transactions_table', 68),
(188, '2022_10_27_145604_create_emergency_contacts_table', 68),
(189, '2022_10_29_182930_add_is_pause_cause_to_orders_table', 68),
(190, '2022_10_31_150604_add_address_phone_country_code_column_to_delivery_men_table', 68),
(191, '2022_11_05_185726_add_order_id_to_reviews_table', 68),
(192, '2022_11_07_190749_create_deliveryman_notifications_table', 68),
(193, '2022_11_08_132745_change_transaction_note_type_to_withdraw_requests_table', 68),
(194, '2022_11_10_193747_chenge_order_amount_seller_amount_admin_commission_delivery_charge_tax_toorder_transactions_table', 68),
(195, '2022_12_17_035723_few_field_add_to_coupons_table', 69),
(196, '2022_12_26_231606_add_coupon_discount_bearer_and_admin_commission_to_orders', 69),
(197, '2023_01_04_003034_alter_billing_addresses_change_zip', 69),
(198, '2023_01_05_121600_change_id_to_transactions_table', 69),
(199, '2023_02_02_113330_create_product_tag_table', 70),
(200, '2023_02_02_114518_create_tags_table', 70),
(201, '2023_02_02_152248_add_tax_model_to_products_table', 70),
(202, '2023_02_02_152718_add_tax_model_to_order_details_table', 70),
(203, '2023_02_02_171034_add_tax_type_to_carts', 70),
(204, '2023_02_06_124447_add_color_image_column_to_products_table', 70),
(205, '2023_02_07_120136_create_withdrawal_methods_table', 70),
(206, '2023_02_07_175939_add_withdrawal_method_id_and_withdrawal_method_fields_to_withdraw_requests_table', 70),
(207, '2023_02_08_143314_add_vacation_start_and_vacation_end_and_vacation_not_column_to_shops_table', 70),
(208, '2023_02_09_104656_add_payment_by_and_payment_not_to_orders_table', 70),
(209, '2023_03_27_150723_add_expires_at_to_phone_or_email_verifications', 71),
(210, '2023_04_17_095721_create_shop_followers_table', 71),
(211, '2023_04_17_111249_add_bottom_banner_to_shops_table', 71),
(212, '2023_04_20_125423_create_product_compares_table', 71),
(213, '2023_04_30_165642_add_category_sub_category_and_sub_sub_category_add_in_product_table', 71),
(214, '2023_05_16_131006_add_expires_at_to_password_resets', 71),
(215, '2023_05_17_044243_add_visit_count_to_tags_table', 71),
(216, '2023_05_18_000403_add_title_and_subtitle_and_background_color_and_button_text_to_banners_table', 71),
(217, '2023_05_21_111300_add_login_hit_count_and_is_temp_blocked_and_temp_block_time_to_users_table', 71),
(218, '2023_05_21_111600_add_login_hit_count_and_is_temp_blocked_and_temp_block_time_to_phone_or_email_verifications_table', 71),
(219, '2023_05_21_112215_add_login_hit_count_and_is_temp_blocked_and_temp_block_time_to_password_resets_table', 71),
(220, '2023_06_04_210726_attachment_lenght_change_to_reviews_table', 71),
(221, '2023_06_05_115153_add_referral_code_and_referred_by_to_users_table', 72),
(222, '2023_06_21_002658_add_offer_banner_to_shops_table', 72),
(223, '2023_07_08_210747_create_most_demandeds_table', 72),
(224, '2023_07_31_111419_add_minimum_order_amount_to_sellers_table', 72),
(225, '2023_08_03_105256_create_offline_payment_methods_table', 72),
(226, '2023_08_07_131013_add_is_guest_column_to_carts_table', 72),
(227, '2023_08_07_170601_create_offline_payments_table', 72),
(228, '2023_08_12_102355_create_add_fund_bonus_categories_table', 72),
(229, '2023_08_12_215346_create_guest_users_table', 72),
(230, '2023_08_12_215659_add_is_guest_column_to_orders_table', 72),
(231, '2023_08_12_215933_add_is_guest_column_to_shipping_addresses_table', 72),
(232, '2023_08_15_000957_add_email_column_toshipping_address_table', 72),
(233, '2023_08_17_222330_add_identify_related_columns_to_admins_table', 72),
(234, '2023_08_20_230624_add_sent_by_and_send_to_in_notifications_table', 72),
(235, '2023_08_20_230911_create_notification_seens_table', 72),
(236, '2023_08_21_042331_add_theme_to_banners_table', 72),
(237, '2023_08_24_150009_add_free_delivery_over_amount_and_status_to_seller_table', 72),
(238, '2023_08_26_161214_add_is_shipping_free_to_orders_table', 72),
(239, '2023_08_26_173523_add_payment_method_column_to_wallet_transactions_table', 72),
(240, '2023_08_26_204653_add_verification_status_column_to_orders_table', 72),
(241, '2023_08_26_225113_create_order_delivery_verifications_table', 72),
(242, '2023_09_03_212200_add_free_delivery_responsibility_column_to_orders_table', 72),
(243, '2023_09_23_153314_add_shipping_responsibility_column_to_orders_table', 72),
(244, '2023_09_25_152733_create_digital_product_otp_verifications_table', 72),
(245, '2023_09_27_191638_add_attachment_column_to_support_ticket_convs_table', 73),
(246, '2023_10_01_205117_add_attachment_column_to_chattings_table', 73),
(247, '2023_10_07_182714_create_notification_messages_table', 73),
(248, '2023_10_21_113354_add_app_language_column_to_users_table', 73),
(249, '2023_10_21_123433_add_app_language_column_to_sellers_table', 73),
(250, '2023_10_21_124657_add_app_language_column_to_delivery_men_table', 73),
(251, '2023_10_22_130225_add_attachment_to_support_tickets_table', 73),
(252, '2023_10_25_113233_make_message_nullable_in_chattings_table', 73),
(253, '2023_10_30_152005_make_attachment_column_type_change_to_reviews_table', 73),
(254, '2024_01_14_192546_add_slug_to_shops_table', 74),
(255, '2024_01_25_175421_add_country_code_to_emergency_contacts_table', 75),
(256, '2024_02_01_200417_add_denied_count_and_approved_count_to_refund_requests_table', 75),
(257, '2024_03_11_130425_add_seen_notification_and_notification_receiver_to_chattings_table', 76),
(258, '2024_03_12_123322_update_images_column_in_refund_requests_table', 76),
(259, '2024_03_21_134659_change_denied_note_column_type_to_text', 76),
(260, '2024_04_03_093637_create_email_templates_table', 77),
(261, '2024_04_17_102137_add_is_checked_column_to_carts_table', 77),
(262, '2024_04_23_130436_create_vendor_registration_reasons_table', 77),
(263, '2024_04_24_093932_add_type_to_help_topics_table', 77),
(264, '2024_05_20_133216_create_review_replies_table', 78),
(265, '2024_05_20_163043_add_image_alt_text_to_brands_table', 78),
(266, '2024_05_26_152030_create_digital_product_variations_table', 78),
(267, '2024_05_26_152339_create_product_seos_table', 78),
(268, '2024_05_27_184401_add_digital_product_file_types_and_digital_product_extensions_to_products_table', 78),
(269, '2024_05_30_101603_create_storages_table', 78),
(270, '2024_06_10_174952_create_robots_meta_contents_table', 78),
(271, '2024_06_12_105137_create_error_logs_table', 78),
(272, '2024_07_03_130217_add_storage_type_columns_to_product_table', 78),
(273, '2024_07_03_153301_add_icon_storage_type_to_catogory_table', 78),
(274, '2024_07_03_171214_add_image_storage_type_to_brands_table', 78),
(275, '2024_07_03_185048_add_storage_type_columns_to_shop_table', 78),
(276, '2024_08_05_230931_create_store_user_table', 79);

-- --------------------------------------------------------

--
-- Structure de la table `most_demandeds`
--

CREATE TABLE `most_demandeds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `banner` varchar(255) NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sent_by` varchar(255) NOT NULL DEFAULT 'system',
  `sent_to` varchar(255) NOT NULL DEFAULT 'customer',
  `title` varchar(100) DEFAULT NULL,
  `description` varchar(191) DEFAULT NULL,
  `notification_count` int(11) NOT NULL DEFAULT 0,
  `image` varchar(50) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notification_messages`
--

CREATE TABLE `notification_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_type` varchar(191) DEFAULT NULL,
  `key` varchar(191) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `notification_messages`
--

INSERT INTO `notification_messages` (`id`, `user_type`, `key`, `message`, `status`, `created_at`, `updated_at`) VALUES
(1, 'customer', 'order_pending_message', 'order pen message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(2, 'customer', 'order_confirmation_message', 'Order con Message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(3, 'customer', 'order_processing_message', 'Order pro Message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(4, 'customer', 'out_for_delivery_message', 'Order ouut Message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(5, 'customer', 'order_delivered_message', 'Order del Message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(6, 'customer', 'order_returned_message', 'Order hh Message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(7, 'customer', 'order_failed_message', 'Order fa Message', 0, '2023-10-30 11:02:55', '2024-05-18 10:57:03'),
(8, 'customer', 'order_canceled', '', 0, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(9, 'customer', 'order_refunded_message', 'customize your order refunded message message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(10, 'customer', 'refund_request_canceled_message', 'customize your refund request canceled message message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(11, 'customer', 'message_from_delivery_man', 'customize your message from delivery man message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(12, 'customer', 'message_from_seller', 'customize your message from seller message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(13, 'customer', 'fund_added_by_admin_message', 'customize your fund added by admin message message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(14, 'seller', 'new_order_message', 'customize your new order message message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(15, 'seller', 'refund_request_message', 'customize your refund request message message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(16, 'seller', 'order_edit_message', 'customize your order edit message message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(17, 'seller', 'withdraw_request_status_message', 'customize your withdraw request status message message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(18, 'seller', 'message_from_customer', 'customize your message from customer message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(19, 'seller', 'delivery_man_assign_by_admin_message', 'customize your delivery man assign by admin message message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(20, 'seller', 'order_delivered_message', 'customize your order delivered message message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(21, 'seller', 'order_canceled', 'customize your order canceled message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(22, 'seller', 'order_refunded_message', 'customize your order refunded message message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(23, 'seller', 'refund_request_canceled_message', 'customize your refund request canceled message message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(24, 'seller', 'refund_request_status_changed_by_admin', 'customize your refund request status changed by admin message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(25, 'delivery_man', 'new_order_assigned_message', '', 0, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(26, 'delivery_man', 'expected_delivery_date', '', 0, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(27, 'delivery_man', 'delivery_man_charge', 'customize your delivery man charge message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(28, 'delivery_man', 'order_canceled', 'customize your order canceled message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(29, 'delivery_man', 'order_rescheduled_message', 'customize your order rescheduled message message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(30, 'delivery_man', 'order_edit_message', 'customize your order edit message message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(31, 'delivery_man', 'message_from_seller', 'customize your message from seller message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(32, 'delivery_man', 'message_from_admin', 'customize your message from admin message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(33, 'delivery_man', 'message_from_customer', 'customize your message from customer message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(34, 'delivery_man', 'cash_collect_by_admin_message', 'customize your cash collect by admin message message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(35, 'delivery_man', 'cash_collect_by_seller_message', 'customize your cash collect by seller message message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(36, 'delivery_man', 'withdraw_request_status_message', 'customize your withdraw request status message message', 1, '2023-10-30 11:02:55', '2023-10-30 11:02:55'),
(37, 'seller', 'product_request_approved_message', 'customize your product request approved message message', 1, '2024-02-19 08:35:38', '2024-02-19 08:35:38'),
(38, 'seller', 'product_request_rejected_message', 'customize your product request rejected message message', 1, '2024-02-19 08:35:38', '2024-02-19 08:35:38');

-- --------------------------------------------------------

--
-- Structure de la table `notification_seens`
--

CREATE TABLE `notification_seens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `notification_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('0c6d8aeffe9a8745696c745fb51ddee6371e345d1d5e1c9c8527d512f84556ed6dad3761cacbc3f0', 25, 1, 'LaravelAuthApp', '[]', 0, '2024-08-08 02:22:28', '2024-08-08 02:22:28', '2025-08-08 02:22:28'),
('243f289ea9292563d1917e635328aa5b53a5dbd2270008ac10be9edd0523126ae6cddd35ddbdc46b', 32, 1, 'LaravelAuthApp', '[]', 0, '2024-08-09 21:04:36', '2024-08-09 21:04:36', '2025-08-09 21:04:36'),
('45a0434ea391f3d4dfe0a6eca6d93c189a6ceb9660ca4e806ab8c878ff4d43164d498e07d35c1bb2', 33, 1, 'LaravelAuthApp', '[]', 0, '2024-08-11 04:02:01', '2024-08-11 04:02:01', '2025-08-11 04:02:01'),
('67c2f1ff4f710f32a04c87ed1252b4abf19a5d5c8248ba2d9bef60623bcacea506160293da53826c', 28, 1, 'LaravelAuthApp', '[]', 0, '2024-08-09 20:49:59', '2024-08-09 20:49:59', '2025-08-09 20:49:59'),
('6840b7d4ed685bf2e0dc593affa0bd3b968065f47cc226d39ab09f1422b5a1d9666601f3f60a79c1', 98, 1, 'LaravelAuthApp', '[]', 1, '2021-07-05 09:25:41', '2021-07-05 09:25:41', '2022-07-05 15:25:41'),
('69972d8e62c4966a2a37f7231d1e2563f69bd9f695102a453ddb263210a127cfd81bd31928d43d29', 24, 1, 'LaravelAuthApp', '[]', 1, '2024-08-08 02:17:28', '2024-08-08 02:22:05', '2025-08-08 02:17:28'),
('85af15a0ec73046cfe8505679b3c138364bc19e46bd0adda035b751820c75feddcdeb94346a973c5', 28, 1, 'LaravelAuthApp', '[]', 0, '2024-08-09 20:49:32', '2024-08-09 20:49:32', '2025-08-09 20:49:32'),
('9e6543a9e57f5fdad543cabe1fd7de1fca4e55ced6ae7a8ec52b0993e2ec87ea82ea445c0a9ce704', 23, 1, 'LaravelAuthApp', '[]', 1, '2024-08-08 01:43:57', '2024-08-08 01:47:28', '2025-08-08 01:43:57'),
('b420c280d66f087c1479312f10aec80cc480573eeb45342907a0fac19f84098a6d67f55ef8c03a33', 29, 1, 'LaravelAuthApp', '[]', 0, '2024-08-09 21:00:34', '2024-08-09 21:00:34', '2025-08-09 21:00:34'),
('c25708b678d45bc85e392c04f231467dbadf2d28151b0ccd1af0ac314f3d0c83d33cf6ea13653b54', 29, 1, 'LaravelAuthApp', '[]', 0, '2024-08-09 21:02:37', '2024-08-09 21:02:37', '2025-08-09 21:02:37'),
('c42cdd5ae652b8b2cbac4f2f4b496e889e1a803b08672954c8bbe06722b54160e71dce3e02331544', 98, 1, 'LaravelAuthApp', '[]', 1, '2021-07-05 09:24:36', '2021-07-05 09:24:36', '2022-07-05 15:24:36'),
('ced2b1c79028fd2b7915398c0bbc782356345716662cab9c684dcd0ff01e642fa789840078e0ea39', 36, 1, 'LaravelAuthApp', '[]', 0, '2024-08-12 12:14:16', '2024-08-12 12:14:16', '2025-08-12 12:14:16');

-- --------------------------------------------------------

--
-- Structure de la table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `secret` varchar(100) NOT NULL,
  `redirect` text NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `provider` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`, `provider`) VALUES
(1, NULL, '6amtech', 'GEUx5tqkviM6AAQcz4oi1dcm1KtRdJPgw41lj0eI', 'http://localhost', 1, 0, 0, '2020-10-21 18:27:22', '2020-10-21 18:27:22', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2020-10-21 18:27:23', '2020-10-21 18:27:23');

-- --------------------------------------------------------

--
-- Structure de la table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) NOT NULL,
  `access_token_id` varchar(100) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `offline_payments`
--

CREATE TABLE `offline_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` int(11) NOT NULL,
  `payment_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payment_info`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `offline_payment_methods`
--

CREATE TABLE `offline_payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `method_name` varchar(255) NOT NULL,
  `method_fields` text NOT NULL,
  `method_informations` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` varchar(15) DEFAULT NULL,
  `is_guest` tinyint(4) NOT NULL DEFAULT 0,
  `customer_type` varchar(10) DEFAULT NULL,
  `payment_status` varchar(15) NOT NULL DEFAULT 'unpaid',
  `order_status` varchar(50) NOT NULL DEFAULT 'pending',
  `payment_method` varchar(100) DEFAULT NULL,
  `transaction_ref` varchar(30) DEFAULT NULL,
  `payment_by` varchar(191) DEFAULT NULL,
  `payment_note` text DEFAULT NULL,
  `order_amount` double NOT NULL DEFAULT 0,
  `admin_commission` decimal(8,2) NOT NULL DEFAULT 0.00,
  `is_pause` varchar(20) NOT NULL DEFAULT '0',
  `cause` varchar(191) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `discount_amount` double NOT NULL DEFAULT 0,
  `discount_type` varchar(30) DEFAULT NULL,
  `coupon_code` varchar(191) DEFAULT NULL,
  `coupon_discount_bearer` varchar(191) NOT NULL DEFAULT 'inhouse',
  `shipping_responsibility` varchar(255) DEFAULT NULL,
  `shipping_method_id` bigint(20) NOT NULL DEFAULT 0,
  `shipping_cost` double(8,2) NOT NULL DEFAULT 0.00,
  `is_shipping_free` tinyint(1) NOT NULL DEFAULT 0,
  `order_group_id` varchar(191) NOT NULL DEFAULT 'def-order-group',
  `verification_code` varchar(191) NOT NULL DEFAULT '0',
  `verification_status` tinyint(4) NOT NULL DEFAULT 0,
  `seller_id` bigint(20) DEFAULT NULL,
  `seller_is` varchar(191) DEFAULT NULL,
  `shipping_address_data` text DEFAULT NULL,
  `delivery_man_id` bigint(20) DEFAULT NULL,
  `deliveryman_charge` double NOT NULL DEFAULT 0,
  `expected_delivery_date` date DEFAULT NULL,
  `order_note` text DEFAULT NULL,
  `billing_address` bigint(20) UNSIGNED DEFAULT NULL,
  `billing_address_data` text DEFAULT NULL,
  `order_type` varchar(191) NOT NULL DEFAULT 'default_type',
  `extra_discount` double(8,2) NOT NULL DEFAULT 0.00,
  `extra_discount_type` varchar(191) DEFAULT NULL,
  `free_delivery_bearer` varchar(255) DEFAULT NULL,
  `checked` tinyint(1) NOT NULL DEFAULT 0,
  `shipping_type` varchar(191) DEFAULT NULL,
  `delivery_type` varchar(191) DEFAULT NULL,
  `delivery_service_name` varchar(191) DEFAULT NULL,
  `third_party_delivery_tracking_id` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `is_guest`, `customer_type`, `payment_status`, `order_status`, `payment_method`, `transaction_ref`, `payment_by`, `payment_note`, `order_amount`, `admin_commission`, `is_pause`, `cause`, `shipping_address`, `created_at`, `updated_at`, `discount_amount`, `discount_type`, `coupon_code`, `coupon_discount_bearer`, `shipping_responsibility`, `shipping_method_id`, `shipping_cost`, `is_shipping_free`, `order_group_id`, `verification_code`, `verification_status`, `seller_id`, `seller_is`, `shipping_address_data`, `delivery_man_id`, `deliveryman_charge`, `expected_delivery_date`, `order_note`, `billing_address`, `billing_address_data`, `order_type`, `extra_discount`, `extra_discount_type`, `free_delivery_bearer`, `checked`, `shipping_type`, `delivery_type`, `delivery_service_name`, `third_party_delivery_tracking_id`) VALUES
(100001, '33', 0, 'customer', 'unpaid', 'pending', 'cash_on_delivery', '', NULL, NULL, 205, 0.00, '0', NULL, '2', '2024-08-13 13:51:47', '2024-08-15 10:37:06', 0, NULL, '0', 'inhouse', 'inhouse_shipping', 2, 5.00, 0, '9017-gg6pD-1723557107', '563731', 0, 1, 'admin', '{\"id\":2,\"customer_id\":\"33\",\"is_guest\":false,\"contact_person_name\":\"591136162 591136162\",\"email\":null,\"address_type\":\"home\",\"address\":\"Ouled Yaicch\",\"city\":\"Blida\",\"zip\":\"2001\",\"phone\":\"591136162\",\"created_at\":\"2024-08-13T11:24:29.000000Z\",\"updated_at\":\"2024-08-13T11:24:29.000000Z\",\"state\":null,\"country\":\"\\u0627\\u0644\\u062c\\u0632\\u0627\\u0626\\u0631\",\"latitude\":\"36.51598297061278\",\"longitude\":\"2.8921716660261154\",\"is_billing\":false}', NULL, 0, NULL, NULL, 2, '{\"id\":2,\"customer_id\":\"33\",\"is_guest\":false,\"contact_person_name\":\"591136162 591136162\",\"email\":null,\"address_type\":\"home\",\"address\":\"Ouled Yaicch\",\"city\":\"Blida\",\"zip\":\"2001\",\"phone\":\"591136162\",\"created_at\":\"2024-08-13T11:24:29.000000Z\",\"updated_at\":\"2024-08-13T11:24:29.000000Z\",\"state\":null,\"country\":\"\\u0627\\u0644\\u062c\\u0632\\u0627\\u0626\\u0631\",\"latitude\":\"36.51598297061278\",\"longitude\":\"2.8921716660261154\",\"is_billing\":false}', 'default_type', 0.00, NULL, 'admin', 1, 'order_wise', NULL, NULL, NULL),
(100002, '33', 0, 'customer', 'unpaid', 'pending', 'cash_on_delivery', '', NULL, NULL, 205, 0.00, '0', NULL, '2', '2024-08-15 10:33:28', '2024-08-15 10:37:06', 0, NULL, '0', 'inhouse', 'inhouse_shipping', 2, 5.00, 0, '4081-2Gpsh-1723718008', '108776', 0, 1, 'admin', '{\"id\":2,\"customer_id\":\"33\",\"is_guest\":false,\"contact_person_name\":\"591136162 591136162\",\"email\":null,\"address_type\":\"home\",\"address\":\"Ouled Yaicch\",\"city\":\"Blida\",\"zip\":\"2001\",\"phone\":\"591136162\",\"created_at\":\"2024-08-13T11:24:29.000000Z\",\"updated_at\":\"2024-08-13T11:24:29.000000Z\",\"state\":null,\"country\":\"\\u0627\\u0644\\u062c\\u0632\\u0627\\u0626\\u0631\",\"latitude\":\"36.51598297061278\",\"longitude\":\"2.8921716660261154\",\"is_billing\":false}', NULL, 0, NULL, NULL, 2, '{\"id\":2,\"customer_id\":\"33\",\"is_guest\":false,\"contact_person_name\":\"591136162 591136162\",\"email\":null,\"address_type\":\"home\",\"address\":\"Ouled Yaicch\",\"city\":\"Blida\",\"zip\":\"2001\",\"phone\":\"591136162\",\"created_at\":\"2024-08-13T11:24:29.000000Z\",\"updated_at\":\"2024-08-13T11:24:29.000000Z\",\"state\":null,\"country\":\"\\u0627\\u0644\\u062c\\u0632\\u0627\\u0626\\u0631\",\"latitude\":\"36.51598297061278\",\"longitude\":\"2.8921716660261154\",\"is_billing\":false}', 'default_type', 0.00, NULL, 'admin', 1, 'order_wise', NULL, NULL, NULL),
(100003, '25', 0, 'customer', 'unpaid', 'pending', 'cash_on_delivery', '', NULL, NULL, 1005, 0.00, '0', NULL, NULL, '2024-08-15 18:57:44', '2024-08-15 19:46:54', 0, NULL, '0', 'inhouse', 'inhouse_shipping', 2, 5.00, 0, '4136-j8cMu-1723748264', '790363', 0, 1, 'admin', NULL, NULL, 0, NULL, NULL, NULL, NULL, 'default_type', 0.00, NULL, 'admin', 1, 'order_wise', NULL, NULL, NULL),
(100004, '33', 0, 'customer', 'unpaid', 'pending', 'cash_on_delivery', '', NULL, NULL, 1005, 0.00, '0', NULL, '3', '2024-08-15 21:22:36', '2024-08-15 21:57:15', 0, NULL, '0', 'inhouse', 'inhouse_shipping', 2, 5.00, 0, '5957-JnUZC-1723756956', '728061', 0, 1, 'admin', '{\"id\":3,\"customer_id\":\"33\",\"is_guest\":false,\"contact_person_name\":\"591136162\",\"email\":\"591136162@gmail.com\",\"address_type\":\"home\",\"address\":\"\\u062a\\u064a\\u0645\\u0642\\u062a\\u0646\",\"city\":\"\\u0648\\u0627\\u062f\\u0649 \\u0627\\u0644\\u0634\\u0648\\u0644\\u0649\",\"zip\":\"13011\",\"phone\":\"591136162\",\"created_at\":\"2024-08-15T21:17:19.000000Z\",\"updated_at\":\"2024-08-15T21:17:19.000000Z\",\"state\":null,\"country\":\"\\u0627\\u0644\\u062c\\u0632\\u0627\\u0626\\u0631\",\"latitude\":\"-1.1420878\",\"longitude\":\"34.8666661\",\"is_billing\":false}', NULL, 0, NULL, NULL, 3, '{\"id\":3,\"customer_id\":\"33\",\"is_guest\":false,\"contact_person_name\":\"591136162\",\"email\":\"591136162@gmail.com\",\"address_type\":\"home\",\"address\":\"\\u062a\\u064a\\u0645\\u0642\\u062a\\u0646\",\"city\":\"\\u0648\\u0627\\u062f\\u0649 \\u0627\\u0644\\u0634\\u0648\\u0644\\u0649\",\"zip\":\"13011\",\"phone\":\"591136162\",\"created_at\":\"2024-08-15T21:17:19.000000Z\",\"updated_at\":\"2024-08-15T21:17:19.000000Z\",\"state\":null,\"country\":\"\\u0627\\u0644\\u062c\\u0632\\u0627\\u0626\\u0631\",\"latitude\":\"-1.1420878\",\"longitude\":\"34.8666661\",\"is_billing\":false}', 'default_type', 0.00, NULL, 'admin', 1, 'order_wise', NULL, NULL, NULL),
(100005, '33', 0, 'customer', 'unpaid', 'pending', 'cash_on_delivery', '', NULL, NULL, 1005, 0.00, '0', NULL, '3', '2024-08-15 21:27:58', '2024-08-15 21:57:15', 0, NULL, '0', 'inhouse', 'inhouse_shipping', 2, 5.00, 0, '1162-qhUOF-1723757278', '888388', 0, 1, 'admin', '{\"id\":3,\"customer_id\":\"33\",\"is_guest\":false,\"contact_person_name\":\"591136162\",\"email\":\"591136162@gmail.com\",\"address_type\":\"home\",\"address\":\"\\u062a\\u064a\\u0645\\u0642\\u062a\\u0646\",\"city\":\"\\u0648\\u0627\\u062f\\u0649 \\u0627\\u0644\\u0634\\u0648\\u0644\\u0649\",\"zip\":\"13011\",\"phone\":\"591136162\",\"created_at\":\"2024-08-15T21:17:19.000000Z\",\"updated_at\":\"2024-08-15T21:17:19.000000Z\",\"state\":null,\"country\":\"\\u0627\\u0644\\u062c\\u0632\\u0627\\u0626\\u0631\",\"latitude\":\"-1.1420878\",\"longitude\":\"34.8666661\",\"is_billing\":false}', NULL, 0, NULL, NULL, 3, '{\"id\":3,\"customer_id\":\"33\",\"is_guest\":false,\"contact_person_name\":\"591136162\",\"email\":\"591136162@gmail.com\",\"address_type\":\"home\",\"address\":\"\\u062a\\u064a\\u0645\\u0642\\u062a\\u0646\",\"city\":\"\\u0648\\u0627\\u062f\\u0649 \\u0627\\u0644\\u0634\\u0648\\u0644\\u0649\",\"zip\":\"13011\",\"phone\":\"591136162\",\"created_at\":\"2024-08-15T21:17:19.000000Z\",\"updated_at\":\"2024-08-15T21:17:19.000000Z\",\"state\":null,\"country\":\"\\u0627\\u0644\\u062c\\u0632\\u0627\\u0626\\u0631\",\"latitude\":\"-1.1420878\",\"longitude\":\"34.8666661\",\"is_billing\":false}', 'default_type', 0.00, NULL, 'admin', 1, 'order_wise', NULL, NULL, NULL),
(100006, '25', 0, 'customer', 'unpaid', 'pending', 'cash_on_delivery', '', NULL, NULL, 205, 0.00, '0', NULL, NULL, '2024-08-15 23:05:21', '2024-08-15 23:08:04', 0, NULL, '0', 'inhouse', 'inhouse_shipping', 2, 5.00, 0, '1007-TzvJB-1723763121', '484172', 0, 1, 'seller', NULL, NULL, 0, NULL, NULL, NULL, NULL, 'default_type', 0.00, NULL, NULL, 1, 'order_wise', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `order_delivery_verifications`
--

CREATE TABLE `order_delivery_verifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `image` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `order_details`
--

CREATE TABLE `order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `seller_id` bigint(20) DEFAULT NULL,
  `digital_file_after_sell` varchar(191) DEFAULT NULL,
  `product_details` text DEFAULT NULL,
  `qty` int(11) NOT NULL DEFAULT 0,
  `price` double NOT NULL DEFAULT 0,
  `tax` double NOT NULL DEFAULT 0,
  `discount` double NOT NULL DEFAULT 0,
  `tax_model` varchar(20) NOT NULL DEFAULT 'exclude',
  `delivery_status` varchar(15) NOT NULL DEFAULT 'pending',
  `payment_status` varchar(15) NOT NULL DEFAULT 'unpaid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `shipping_method_id` bigint(20) DEFAULT NULL,
  `variant` varchar(255) DEFAULT NULL,
  `variation` varchar(255) DEFAULT NULL,
  `discount_type` varchar(30) DEFAULT NULL,
  `is_stock_decreased` tinyint(1) NOT NULL DEFAULT 1,
  `refund_request` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `seller_id`, `digital_file_after_sell`, `product_details`, `qty`, `price`, `tax`, `discount`, `tax_model`, `delivery_status`, `payment_status`, `created_at`, `updated_at`, `shipping_method_id`, `variant`, `variation`, `discount_type`, `is_stock_decreased`, `refund_request`) VALUES
(1, 100001, 1, 1, NULL, '{\"id\":1,\"added_by\":\"admin\",\"user_id\":1,\"name\":\"Aluminium Pocket\",\"slug\":\"aluminium-pocket-9dYcyr\",\"product_type\":\"physical\",\"category_ids\":\"[{\\\"id\\\":\\\"1\\\",\\\"position\\\":1}]\",\"category_id\":1,\"sub_category_id\":null,\"sub_sub_category_id\":null,\"brand_id\":1,\"unit\":\"kg\",\"min_qty\":1,\"refundable\":1,\"digital_product_type\":null,\"digital_file_ready\":\"\",\"digital_file_ready_storage_type\":null,\"images\":\"[{\\\"image_name\\\":\\\"2024-08-09-66b6327ecef5b.webp\\\",\\\"storage\\\":\\\"public\\\"}]\",\"color_image\":\"[]\",\"thumbnail\":\"2024-08-09-66b6327ef1fbd.webp\",\"thumbnail_storage_type\":\"public\",\"featured\":null,\"flash_deal\":null,\"video_provider\":\"youtube\",\"video_url\":null,\"colors\":\"[]\",\"variant_product\":0,\"attributes\":\"null\",\"choice_options\":\"[]\",\"variation\":\"[]\",\"digital_product_file_types\":[],\"digital_product_extensions\":[],\"published\":0,\"unit_price\":1200,\"purchase_price\":0,\"tax\":0,\"tax_type\":\"percent\",\"tax_model\":\"include\",\"discount\":1000,\"discount_type\":\"flat\",\"current_stock\":12333,\"minimum_order_qty\":1,\"details\":null,\"free_shipping\":0,\"attachment\":null,\"created_at\":\"2024-08-09T15:15:10.000000Z\",\"updated_at\":\"2024-08-09T15:16:00.000000Z\",\"status\":1,\"featured_status\":1,\"meta_title\":\"Aluminium Pocket\",\"meta_description\":null,\"meta_image\":null,\"request_status\":1,\"denied_note\":null,\"shipping_cost\":0,\"multiply_qty\":0,\"temp_shipping_cost\":null,\"is_shipping_cost_updated\":null,\"code\":\"R1ADI5\",\"digital_file_ready_full_url\":{\"key\":\"\",\"path\":null,\"status\":404},\"digital_variation\":[]}', 1, 1200, 0, 1000, 'include', 'pending', 'unpaid', '2024-08-13 13:51:47', '2024-08-13 13:51:47', NULL, '', '[]', 'discount_on_product', 1, 0),
(2, 100002, 1, 1, NULL, '{\"id\":1,\"added_by\":\"admin\",\"user_id\":1,\"name\":\"Aluminium Pocket\",\"slug\":\"aluminium-pocket-9dYcyr\",\"product_type\":\"physical\",\"category_ids\":\"[{\\\"id\\\":\\\"1\\\",\\\"position\\\":1}]\",\"category_id\":1,\"sub_category_id\":null,\"sub_sub_category_id\":null,\"brand_id\":1,\"unit\":\"kg\",\"min_qty\":1,\"refundable\":1,\"digital_product_type\":null,\"digital_file_ready\":\"\",\"digital_file_ready_storage_type\":null,\"images\":\"[{\\\"image_name\\\":\\\"2024-08-09-66b6327ecef5b.webp\\\",\\\"storage\\\":\\\"public\\\"}]\",\"color_image\":\"[]\",\"thumbnail\":\"2024-08-09-66b6327ef1fbd.webp\",\"thumbnail_storage_type\":\"public\",\"featured\":null,\"flash_deal\":null,\"video_provider\":\"youtube\",\"video_url\":null,\"colors\":\"[]\",\"variant_product\":0,\"attributes\":\"null\",\"choice_options\":\"[]\",\"variation\":\"[]\",\"digital_product_file_types\":[],\"digital_product_extensions\":[],\"published\":0,\"unit_price\":1200,\"purchase_price\":0,\"tax\":0,\"tax_type\":\"percent\",\"tax_model\":\"include\",\"discount\":1000,\"discount_type\":\"flat\",\"current_stock\":12332,\"minimum_order_qty\":1,\"details\":null,\"free_shipping\":0,\"attachment\":null,\"created_at\":\"2024-08-09T15:15:10.000000Z\",\"updated_at\":\"2024-08-13T13:51:47.000000Z\",\"status\":1,\"featured_status\":1,\"meta_title\":\"Aluminium Pocket\",\"meta_description\":null,\"meta_image\":null,\"request_status\":1,\"denied_note\":null,\"shipping_cost\":0,\"multiply_qty\":0,\"temp_shipping_cost\":null,\"is_shipping_cost_updated\":null,\"code\":\"R1ADI5\",\"digital_file_ready_full_url\":{\"key\":\"\",\"path\":null,\"status\":404},\"digital_variation\":[]}', 1, 1200, 0, 1000, 'include', 'pending', 'unpaid', '2024-08-15 10:33:28', '2024-08-15 10:33:28', NULL, '', '[]', 'discount_on_product', 1, 0),
(3, 100003, 4, 1, NULL, '{\"id\":4,\"added_by\":\"admin\",\"user_id\":1,\"name\":\"Men\'s Fleece Lined Hoodie With Bear Print And Drawstring\",\"slug\":\"mens-fleece-lined-hoodie-with-bear-print-and-drawstring-ydksHd\",\"product_type\":\"physical\",\"category_ids\":\"[{\\\"id\\\":\\\"1\\\",\\\"position\\\":1}]\",\"category_id\":1,\"sub_category_id\":null,\"sub_sub_category_id\":null,\"brand_id\":1,\"unit\":\"kg\",\"min_qty\":1,\"refundable\":1,\"digital_product_type\":null,\"digital_file_ready\":\"\",\"digital_file_ready_storage_type\":null,\"images\":\"[\\\"2024-08-15-66be2762e5c63.webp\\\",\\\"2024-08-15-66be27630d9b4.webp\\\",{\\\"image_name\\\":\\\"2024-08-15-66be27632860a.webp\\\",\\\"storage\\\":\\\"public\\\"}]\",\"color_image\":\"[{\\\"color\\\":\\\"A52A2A\\\",\\\"image_name\\\":\\\"2024-08-15-66be2762e5c63.webp\\\",\\\"storage\\\":\\\"public\\\"},{\\\"color\\\":\\\"000000\\\",\\\"image_name\\\":\\\"2024-08-15-66be27630d9b4.webp\\\",\\\"storage\\\":\\\"public\\\"},{\\\"color\\\":null,\\\"image_name\\\":\\\"2024-08-15-66be27632860a.webp\\\",\\\"storage\\\":\\\"public\\\"}]\",\"thumbnail\":\"2024-08-15-66be2763430c7.webp\",\"thumbnail_storage_type\":\"public\",\"featured\":1,\"flash_deal\":null,\"video_provider\":\"youtube\",\"video_url\":null,\"colors\":\"[\\\"#A52A2A\\\",\\\"#000000\\\"]\",\"variant_product\":0,\"attributes\":\"[\\\"1\\\"]\",\"choice_options\":\"[{\\\"name\\\":\\\"choice_1\\\",\\\"title\\\":\\\"Sizes\\\",\\\"options\\\":[\\\"M\\\",\\\"L\\\",\\\"XL\\\",\\\"S\\\"]}]\",\"variation\":\"[{\\\"type\\\":\\\"Brown-M\\\",\\\"price\\\":1000,\\\"sku\\\":\\\"-BROWN-M\\\",\\\"qty\\\":10},{\\\"type\\\":\\\"Brown-L\\\",\\\"price\\\":1000,\\\"sku\\\":\\\"-BROWN-L\\\",\\\"qty\\\":0},{\\\"type\\\":\\\"Brown-XL\\\",\\\"price\\\":1000,\\\"sku\\\":\\\"-BROWN-XL\\\",\\\"qty\\\":10},{\\\"type\\\":\\\"Brown-S\\\",\\\"price\\\":1000,\\\"sku\\\":\\\"-BROWN-S\\\",\\\"qty\\\":0},{\\\"type\\\":\\\"Black-M\\\",\\\"price\\\":1000,\\\"sku\\\":\\\"-BLACK-M\\\",\\\"qty\\\":10},{\\\"type\\\":\\\"Black-L\\\",\\\"price\\\":1000,\\\"sku\\\":\\\"-BLACK-L\\\",\\\"qty\\\":10},{\\\"type\\\":\\\"Black-XL\\\",\\\"price\\\":1000,\\\"sku\\\":\\\"-BLACK-XL\\\",\\\"qty\\\":10},{\\\"type\\\":\\\"Black-S\\\",\\\"price\\\":1000,\\\"sku\\\":\\\"-BLACK-S\\\",\\\"qty\\\":10}]\",\"digital_product_file_types\":[],\"digital_product_extensions\":[],\"published\":0,\"unit_price\":1000,\"purchase_price\":0,\"tax\":0,\"tax_type\":\"percent\",\"tax_model\":\"include\",\"discount\":0,\"discount_type\":\"flat\",\"current_stock\":60,\"minimum_order_qty\":1,\"details\":\"<div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Details:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Drawstring<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Fit Type:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Loose<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Neckline:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Hooded<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Sleeve Type:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Drop Shoulder<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Style:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Casual<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Type:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Pullovers<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Color:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Burgundy<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Pattern Type:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Cartoon<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Sleeve Length:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Long Sleeve<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Length:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Regular<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Fabric:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Slight Stretch<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Material:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Fabric<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Composition:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">100% Polyester<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Care Instructions:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Machine wash, do not dry clean<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Temperature:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Late Fall (10-17\\u2103\\/50-63\\u2109)<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Pockets:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">No<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Lined For Added Warmth:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Yes<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Sheer:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">No<\\/div><\\/div>\",\"free_shipping\":0,\"attachment\":null,\"created_at\":\"2024-08-15T16:05:55.000000Z\",\"updated_at\":\"2024-08-15T16:11:11.000000Z\",\"status\":1,\"featured_status\":1,\"meta_title\":\"Men\'s Fleece Lined Hoodie With Bear Print And Drawstring\",\"meta_description\":\"Details:DrawstringFit Type:LooseNeckline:HoodedSleeve Type:Drop ShoulderStyle:CasualType:PulloversColor:BurgundyPattern Type:CartoonSleeve Length:Long SleeveLen\",\"meta_image\":null,\"request_status\":1,\"denied_note\":null,\"shipping_cost\":0,\"multiply_qty\":0,\"temp_shipping_cost\":null,\"is_shipping_cost_updated\":null,\"code\":\"OOL9C0\",\"digital_file_ready_full_url\":{\"key\":\"\",\"path\":null,\"status\":404},\"digital_variation\":[]}', 1, 1000, 0, 0, 'include', 'pending', 'unpaid', '2024-08-15 18:57:44', '2024-08-15 18:57:44', NULL, 'Brown-M', '{\"color\":\"Brown\",\"Sizes\":\"M\"}', 'discount_on_product', 1, 0),
(4, 100004, 4, 1, NULL, '{\"id\":4,\"added_by\":\"admin\",\"user_id\":1,\"name\":\"Men\'s Fleece Lined Hoodie With Bear Print And Drawstring\",\"slug\":\"mens-fleece-lined-hoodie-with-bear-print-and-drawstring-ydksHd\",\"product_type\":\"physical\",\"category_ids\":\"[{\\\"id\\\":\\\"1\\\",\\\"position\\\":1}]\",\"category_id\":1,\"sub_category_id\":null,\"sub_sub_category_id\":null,\"brand_id\":1,\"unit\":\"kg\",\"min_qty\":1,\"refundable\":1,\"digital_product_type\":null,\"digital_file_ready\":\"\",\"digital_file_ready_storage_type\":null,\"images\":\"[\\\"2024-08-15-66be2762e5c63.webp\\\",\\\"2024-08-15-66be27630d9b4.webp\\\",{\\\"image_name\\\":\\\"2024-08-15-66be27632860a.webp\\\",\\\"storage\\\":\\\"public\\\"}]\",\"color_image\":\"[{\\\"color\\\":\\\"A52A2A\\\",\\\"image_name\\\":\\\"2024-08-15-66be2762e5c63.webp\\\",\\\"storage\\\":\\\"public\\\"},{\\\"color\\\":\\\"000000\\\",\\\"image_name\\\":\\\"2024-08-15-66be27630d9b4.webp\\\",\\\"storage\\\":\\\"public\\\"},{\\\"color\\\":null,\\\"image_name\\\":\\\"2024-08-15-66be27632860a.webp\\\",\\\"storage\\\":\\\"public\\\"}]\",\"thumbnail\":\"2024-08-15-66be2763430c7.webp\",\"thumbnail_storage_type\":\"public\",\"featured\":1,\"flash_deal\":null,\"video_provider\":\"youtube\",\"video_url\":null,\"colors\":\"[\\\"#A52A2A\\\",\\\"#000000\\\"]\",\"variant_product\":0,\"attributes\":\"[\\\"1\\\"]\",\"choice_options\":\"[{\\\"name\\\":\\\"choice_1\\\",\\\"title\\\":\\\"Sizes\\\",\\\"options\\\":[\\\"M\\\",\\\"L\\\",\\\"XL\\\",\\\"S\\\"]}]\",\"variation\":\"[{\\\"type\\\":\\\"Brown-M\\\",\\\"price\\\":1000,\\\"sku\\\":\\\"-BROWN-M\\\",\\\"qty\\\":9},{\\\"type\\\":\\\"Brown-L\\\",\\\"price\\\":1000,\\\"sku\\\":\\\"-BROWN-L\\\",\\\"qty\\\":0},{\\\"type\\\":\\\"Brown-XL\\\",\\\"price\\\":1000,\\\"sku\\\":\\\"-BROWN-XL\\\",\\\"qty\\\":10},{\\\"type\\\":\\\"Brown-S\\\",\\\"price\\\":1000,\\\"sku\\\":\\\"-BROWN-S\\\",\\\"qty\\\":0},{\\\"type\\\":\\\"Black-M\\\",\\\"price\\\":1000,\\\"sku\\\":\\\"-BLACK-M\\\",\\\"qty\\\":10},{\\\"type\\\":\\\"Black-L\\\",\\\"price\\\":1000,\\\"sku\\\":\\\"-BLACK-L\\\",\\\"qty\\\":10},{\\\"type\\\":\\\"Black-XL\\\",\\\"price\\\":1000,\\\"sku\\\":\\\"-BLACK-XL\\\",\\\"qty\\\":10},{\\\"type\\\":\\\"Black-S\\\",\\\"price\\\":1000,\\\"sku\\\":\\\"-BLACK-S\\\",\\\"qty\\\":10}]\",\"digital_product_file_types\":[],\"digital_product_extensions\":[],\"published\":0,\"unit_price\":1000,\"purchase_price\":0,\"tax\":0,\"tax_type\":\"percent\",\"tax_model\":\"include\",\"discount\":0,\"discount_type\":\"flat\",\"current_stock\":59,\"minimum_order_qty\":1,\"details\":\"<div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Details:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Drawstring<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Fit Type:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Loose<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Neckline:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Hooded<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Sleeve Type:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Drop Shoulder<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Style:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Casual<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Type:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Pullovers<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Color:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Burgundy<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Pattern Type:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Cartoon<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Sleeve Length:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Long Sleeve<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Length:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Regular<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Fabric:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Slight Stretch<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Material:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Fabric<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Composition:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">100% Polyester<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Care Instructions:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Machine wash, do not dry clean<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Temperature:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Late Fall (10-17\\u2103\\/50-63\\u2109)<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Pockets:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">No<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Lined For Added Warmth:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Yes<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Sheer:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">No<\\/div><\\/div>\",\"free_shipping\":0,\"attachment\":null,\"created_at\":\"2024-08-15T16:05:55.000000Z\",\"updated_at\":\"2024-08-15T18:57:44.000000Z\",\"status\":1,\"featured_status\":1,\"meta_title\":\"Men\'s Fleece Lined Hoodie With Bear Print And Drawstring\",\"meta_description\":\"Details:DrawstringFit Type:LooseNeckline:HoodedSleeve Type:Drop ShoulderStyle:CasualType:PulloversColor:BurgundyPattern Type:CartoonSleeve Length:Long SleeveLen\",\"meta_image\":null,\"request_status\":1,\"denied_note\":null,\"shipping_cost\":0,\"multiply_qty\":0,\"temp_shipping_cost\":null,\"is_shipping_cost_updated\":null,\"code\":\"OOL9C0\",\"digital_file_ready_full_url\":{\"key\":\"\",\"path\":null,\"status\":404},\"digital_variation\":[]}', 1, 1000, 0, 0, 'include', 'pending', 'unpaid', '2024-08-15 21:22:36', '2024-08-15 21:22:36', NULL, 'Brown-M', '{\"color\":\"Brown\",\"Sizes\":\"M\"}', 'discount_on_product', 1, 0),
(5, 100005, 4, 1, NULL, '{\"id\":4,\"added_by\":\"admin\",\"user_id\":1,\"name\":\"Men\'s Fleece Lined Hoodie With Bear Print And Drawstring\",\"slug\":\"mens-fleece-lined-hoodie-with-bear-print-and-drawstring-ydksHd\",\"product_type\":\"physical\",\"category_ids\":\"[{\\\"id\\\":\\\"1\\\",\\\"position\\\":1}]\",\"category_id\":1,\"sub_category_id\":null,\"sub_sub_category_id\":null,\"brand_id\":1,\"unit\":\"kg\",\"min_qty\":1,\"refundable\":1,\"digital_product_type\":null,\"digital_file_ready\":\"\",\"digital_file_ready_storage_type\":null,\"images\":\"[\\\"2024-08-15-66be2762e5c63.webp\\\",\\\"2024-08-15-66be27630d9b4.webp\\\",{\\\"image_name\\\":\\\"2024-08-15-66be27632860a.webp\\\",\\\"storage\\\":\\\"public\\\"}]\",\"color_image\":\"[{\\\"color\\\":\\\"A52A2A\\\",\\\"image_name\\\":\\\"2024-08-15-66be2762e5c63.webp\\\",\\\"storage\\\":\\\"public\\\"},{\\\"color\\\":\\\"000000\\\",\\\"image_name\\\":\\\"2024-08-15-66be27630d9b4.webp\\\",\\\"storage\\\":\\\"public\\\"},{\\\"color\\\":null,\\\"image_name\\\":\\\"2024-08-15-66be27632860a.webp\\\",\\\"storage\\\":\\\"public\\\"}]\",\"thumbnail\":\"2024-08-15-66be2763430c7.webp\",\"thumbnail_storage_type\":\"public\",\"featured\":1,\"flash_deal\":null,\"video_provider\":\"youtube\",\"video_url\":null,\"colors\":\"[\\\"#A52A2A\\\",\\\"#000000\\\"]\",\"variant_product\":0,\"attributes\":\"[\\\"1\\\"]\",\"choice_options\":\"[{\\\"name\\\":\\\"choice_1\\\",\\\"title\\\":\\\"Sizes\\\",\\\"options\\\":[\\\"M\\\",\\\"L\\\",\\\"XL\\\",\\\"S\\\"]}]\",\"variation\":\"[{\\\"type\\\":\\\"Brown-M\\\",\\\"price\\\":1000,\\\"sku\\\":\\\"-BROWN-M\\\",\\\"qty\\\":8},{\\\"type\\\":\\\"Brown-L\\\",\\\"price\\\":1000,\\\"sku\\\":\\\"-BROWN-L\\\",\\\"qty\\\":0},{\\\"type\\\":\\\"Brown-XL\\\",\\\"price\\\":1000,\\\"sku\\\":\\\"-BROWN-XL\\\",\\\"qty\\\":10},{\\\"type\\\":\\\"Brown-S\\\",\\\"price\\\":1000,\\\"sku\\\":\\\"-BROWN-S\\\",\\\"qty\\\":0},{\\\"type\\\":\\\"Black-M\\\",\\\"price\\\":1000,\\\"sku\\\":\\\"-BLACK-M\\\",\\\"qty\\\":10},{\\\"type\\\":\\\"Black-L\\\",\\\"price\\\":1000,\\\"sku\\\":\\\"-BLACK-L\\\",\\\"qty\\\":10},{\\\"type\\\":\\\"Black-XL\\\",\\\"price\\\":1000,\\\"sku\\\":\\\"-BLACK-XL\\\",\\\"qty\\\":10},{\\\"type\\\":\\\"Black-S\\\",\\\"price\\\":1000,\\\"sku\\\":\\\"-BLACK-S\\\",\\\"qty\\\":10}]\",\"digital_product_file_types\":[],\"digital_product_extensions\":[],\"published\":0,\"unit_price\":1000,\"purchase_price\":0,\"tax\":0,\"tax_type\":\"percent\",\"tax_model\":\"include\",\"discount\":0,\"discount_type\":\"flat\",\"current_stock\":58,\"minimum_order_qty\":1,\"details\":\"<div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Details:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Drawstring<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Fit Type:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Loose<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Neckline:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Hooded<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Sleeve Type:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Drop Shoulder<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Style:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Casual<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Type:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Pullovers<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Color:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Burgundy<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Pattern Type:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Cartoon<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Sleeve Length:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Long Sleeve<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Length:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Regular<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Fabric:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Slight Stretch<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Material:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Fabric<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Composition:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">100% Polyester<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Care Instructions:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Machine wash, do not dry clean<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Temperature:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Late Fall (10-17\\u2103\\/50-63\\u2109)<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Pockets:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">No<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Lined For Added Warmth:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">Yes<span dir=\\\"ltr\\\"><\\/span><\\/div><\\/div><div class=\\\"product-intro__description-table-item\\\" tabindex=\\\"0\\\" role=\\\"text\\\" style=\\\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\\\"><div class=\\\"key\\\" style=\\\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\\\">Sheer:<\\/div><div class=\\\"val\\\" style=\\\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\\\">No<\\/div><\\/div>\",\"free_shipping\":0,\"attachment\":null,\"created_at\":\"2024-08-15T16:05:55.000000Z\",\"updated_at\":\"2024-08-15T21:22:36.000000Z\",\"status\":1,\"featured_status\":1,\"meta_title\":\"Men\'s Fleece Lined Hoodie With Bear Print And Drawstring\",\"meta_description\":\"Details:DrawstringFit Type:LooseNeckline:HoodedSleeve Type:Drop ShoulderStyle:CasualType:PulloversColor:BurgundyPattern Type:CartoonSleeve Length:Long SleeveLen\",\"meta_image\":null,\"request_status\":1,\"denied_note\":null,\"shipping_cost\":0,\"multiply_qty\":0,\"temp_shipping_cost\":null,\"is_shipping_cost_updated\":null,\"code\":\"OOL9C0\",\"digital_file_ready_full_url\":{\"key\":\"\",\"path\":null,\"status\":404},\"digital_variation\":[]}', 1, 1000, 0, 0, 'include', 'pending', 'unpaid', '2024-08-15 21:27:58', '2024-08-15 21:27:58', NULL, 'Brown-M', '{\"color\":\"Brown\",\"Sizes\":\"M\"}', 'discount_on_product', 1, 0),
(6, 100006, 3, 1, NULL, '{\"id\":3,\"added_by\":\"seller\",\"user_id\":1,\"name\":\"Shoes\",\"slug\":\"shoes-jqbG7c\",\"product_type\":\"physical\",\"category_ids\":\"[{\\\"id\\\":\\\"1\\\",\\\"position\\\":1}]\",\"category_id\":1,\"sub_category_id\":null,\"sub_sub_category_id\":null,\"brand_id\":1,\"unit\":\"kg\",\"min_qty\":1,\"refundable\":1,\"digital_product_type\":null,\"digital_file_ready\":\"\",\"digital_file_ready_storage_type\":null,\"images\":\"[{\\\"image_name\\\":\\\"2024-08-11-66b92f91d55d3.webp\\\",\\\"storage\\\":\\\"public\\\"}]\",\"color_image\":\"[]\",\"thumbnail\":\"2024-08-11-66b92f9225b5b.webp\",\"thumbnail_storage_type\":\"public\",\"featured\":1,\"flash_deal\":null,\"video_provider\":\"youtube\",\"video_url\":null,\"colors\":\"[]\",\"variant_product\":0,\"attributes\":\"null\",\"choice_options\":\"[]\",\"variation\":\"[]\",\"digital_product_file_types\":[],\"digital_product_extensions\":[],\"published\":0,\"unit_price\":1200,\"purchase_price\":0,\"tax\":0,\"tax_type\":\"percent\",\"tax_model\":\"include\",\"discount\":1000,\"discount_type\":\"flat\",\"current_stock\":20,\"minimum_order_qty\":1,\"details\":\"<p>dd<\\/p>\",\"free_shipping\":0,\"attachment\":null,\"created_at\":\"2024-08-11T21:39:30.000000Z\",\"updated_at\":\"2024-08-15T13:56:32.000000Z\",\"status\":1,\"featured_status\":1,\"meta_title\":\"Shoes\",\"meta_description\":\"dd\",\"meta_image\":null,\"request_status\":1,\"denied_note\":null,\"shipping_cost\":0,\"multiply_qty\":0,\"temp_shipping_cost\":null,\"is_shipping_cost_updated\":null,\"code\":\"QRD14E\",\"digital_file_ready_full_url\":{\"key\":\"\",\"path\":null,\"status\":404},\"digital_variation\":[]}', 1, 1200, 0, 1000, 'include', 'pending', 'unpaid', '2024-08-15 23:05:21', '2024-08-15 23:05:21', NULL, '', '[]', 'discount_on_product', 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `order_expected_delivery_histories`
--

CREATE TABLE `order_expected_delivery_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `user_type` varchar(191) NOT NULL,
  `expected_delivery_date` date NOT NULL,
  `cause` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `order_status_histories`
--

CREATE TABLE `order_status_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `user_type` varchar(191) NOT NULL,
  `status` varchar(191) NOT NULL,
  `cause` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `order_status_histories`
--

INSERT INTO `order_status_histories` (`id`, `order_id`, `user_id`, `user_type`, `status`, `cause`, `created_at`, `updated_at`) VALUES
(1, 100001, 33, 'customer', 'pending', NULL, '2024-08-13 13:51:47', '2024-08-13 13:51:47'),
(2, 100002, 33, 'customer', 'pending', NULL, '2024-08-15 10:33:28', '2024-08-15 10:33:28'),
(3, 100003, 25, 'customer', 'pending', NULL, '2024-08-15 18:57:44', '2024-08-15 18:57:44'),
(4, 100004, 33, 'customer', 'pending', NULL, '2024-08-15 21:22:36', '2024-08-15 21:22:36'),
(5, 100005, 33, 'customer', 'pending', NULL, '2024-08-15 21:27:58', '2024-08-15 21:27:58'),
(6, 100006, 25, 'customer', 'pending', NULL, '2024-08-15 23:05:21', '2024-08-15 23:05:21');

-- --------------------------------------------------------

--
-- Structure de la table `order_transactions`
--

CREATE TABLE `order_transactions` (
  `seller_id` bigint(20) NOT NULL,
  `order_id` bigint(20) NOT NULL,
  `order_amount` decimal(50,2) NOT NULL DEFAULT 0.00,
  `seller_amount` decimal(50,2) NOT NULL DEFAULT 0.00,
  `admin_commission` decimal(50,2) NOT NULL DEFAULT 0.00,
  `received_by` varchar(191) NOT NULL,
  `status` varchar(191) DEFAULT NULL,
  `delivery_charge` decimal(50,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(50,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `customer_id` bigint(20) DEFAULT NULL,
  `seller_is` varchar(191) DEFAULT NULL,
  `delivered_by` varchar(191) NOT NULL DEFAULT 'admin',
  `payment_method` varchar(191) DEFAULT NULL,
  `transaction_id` varchar(191) DEFAULT NULL,
  `id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `password_resets`
--

CREATE TABLE `password_resets` (
  `identity` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `otp_hit_count` tinyint(4) NOT NULL DEFAULT 0,
  `is_temp_blocked` tinyint(1) NOT NULL DEFAULT 0,
  `temp_block_time` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_type` varchar(191) NOT NULL DEFAULT 'customer',
  `id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `payment_requests`
--

CREATE TABLE `payment_requests` (
  `id` char(36) NOT NULL,
  `payer_id` varchar(64) DEFAULT NULL,
  `receiver_id` varchar(64) DEFAULT NULL,
  `payment_amount` decimal(24,2) NOT NULL DEFAULT 0.00,
  `gateway_callback_url` varchar(191) DEFAULT NULL,
  `success_hook` varchar(100) DEFAULT NULL,
  `failure_hook` varchar(100) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `currency_code` varchar(20) NOT NULL DEFAULT 'USD',
  `payment_method` varchar(50) DEFAULT NULL,
  `additional_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `is_paid` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payer_information` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `external_redirect_link` varchar(255) DEFAULT NULL,
  `receiver_information` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `attribute_id` varchar(64) DEFAULT NULL,
  `attribute` varchar(255) DEFAULT NULL,
  `payment_platform` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `paytabs_invoices`
--

CREATE TABLE `paytabs_invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `result` text NOT NULL,
  `response_code` int(10) UNSIGNED NOT NULL,
  `pt_invoice_id` int(10) UNSIGNED DEFAULT NULL,
  `amount` double(8,2) DEFAULT NULL,
  `currency` varchar(191) DEFAULT NULL,
  `transaction_id` int(10) UNSIGNED DEFAULT NULL,
  `card_brand` varchar(191) DEFAULT NULL,
  `card_first_six_digits` int(10) UNSIGNED DEFAULT NULL,
  `card_last_four_digits` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `phone_or_email_verifications`
--

CREATE TABLE `phone_or_email_verifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `phone_or_email` varchar(191) DEFAULT NULL,
  `token` varchar(191) DEFAULT NULL,
  `otp_hit_count` tinyint(4) NOT NULL DEFAULT 0,
  `is_temp_blocked` tinyint(1) NOT NULL DEFAULT 0,
  `temp_block_time` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `phone_or_email_verifications`
--

INSERT INTO `phone_or_email_verifications` (`id`, `phone_or_email`, `token`, `otp_hit_count`, `is_temp_blocked`, `temp_block_time`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'youceffekhar92@gmail.com', '7689', 0, 0, NULL, NULL, '2024-08-11 15:39:14', '2024-08-11 15:39:14'),
(2, 'mahdiharzallah21@gmail.com', '2119', 0, 0, NULL, NULL, '2024-08-11 21:39:52', '2024-08-11 21:39:52');

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `added_by` varchar(191) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `name` varchar(80) DEFAULT NULL,
  `slug` varchar(120) DEFAULT NULL,
  `product_type` varchar(20) NOT NULL DEFAULT 'physical',
  `category_ids` varchar(80) DEFAULT NULL,
  `category_id` varchar(191) DEFAULT NULL,
  `sub_category_id` varchar(191) DEFAULT NULL,
  `sub_sub_category_id` varchar(191) DEFAULT NULL,
  `brand_id` bigint(20) DEFAULT NULL,
  `unit` varchar(191) DEFAULT NULL,
  `min_qty` int(11) NOT NULL DEFAULT 1,
  `refundable` tinyint(1) NOT NULL DEFAULT 1,
  `digital_product_type` varchar(30) DEFAULT NULL,
  `digital_file_ready` varchar(191) DEFAULT NULL,
  `digital_file_ready_storage_type` varchar(10) DEFAULT 'public',
  `images` longtext DEFAULT NULL,
  `color_image` text NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `thumbnail_storage_type` varchar(10) DEFAULT 'public',
  `featured` varchar(255) DEFAULT NULL,
  `flash_deal` varchar(255) DEFAULT NULL,
  `video_provider` varchar(30) DEFAULT NULL,
  `video_url` varchar(150) DEFAULT NULL,
  `colors` varchar(150) DEFAULT NULL,
  `variant_product` tinyint(1) NOT NULL DEFAULT 0,
  `attributes` varchar(255) DEFAULT NULL,
  `choice_options` text DEFAULT NULL,
  `variation` text DEFAULT NULL,
  `digital_product_file_types` longtext DEFAULT NULL,
  `digital_product_extensions` longtext DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `unit_price` double NOT NULL DEFAULT 0,
  `purchase_price` double NOT NULL DEFAULT 0,
  `tax` varchar(191) NOT NULL DEFAULT '0.00',
  `tax_type` varchar(80) DEFAULT NULL,
  `tax_model` varchar(20) NOT NULL DEFAULT 'exclude',
  `discount` varchar(191) NOT NULL DEFAULT '0.00',
  `discount_type` varchar(80) DEFAULT NULL,
  `current_stock` int(11) DEFAULT NULL,
  `minimum_order_qty` int(11) NOT NULL DEFAULT 1,
  `details` text DEFAULT NULL,
  `free_shipping` tinyint(1) NOT NULL DEFAULT 0,
  `attachment` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `featured_status` tinyint(1) NOT NULL DEFAULT 1,
  `meta_title` varchar(191) DEFAULT NULL,
  `meta_description` varchar(191) DEFAULT NULL,
  `meta_image` varchar(191) DEFAULT NULL,
  `request_status` tinyint(1) NOT NULL DEFAULT 0,
  `denied_note` text DEFAULT NULL,
  `shipping_cost` double(8,2) DEFAULT NULL,
  `multiply_qty` tinyint(1) DEFAULT NULL,
  `temp_shipping_cost` double(8,2) DEFAULT NULL,
  `is_shipping_cost_updated` tinyint(1) DEFAULT NULL,
  `code` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `added_by`, `user_id`, `name`, `slug`, `product_type`, `category_ids`, `category_id`, `sub_category_id`, `sub_sub_category_id`, `brand_id`, `unit`, `min_qty`, `refundable`, `digital_product_type`, `digital_file_ready`, `digital_file_ready_storage_type`, `images`, `color_image`, `thumbnail`, `thumbnail_storage_type`, `featured`, `flash_deal`, `video_provider`, `video_url`, `colors`, `variant_product`, `attributes`, `choice_options`, `variation`, `digital_product_file_types`, `digital_product_extensions`, `published`, `unit_price`, `purchase_price`, `tax`, `tax_type`, `tax_model`, `discount`, `discount_type`, `current_stock`, `minimum_order_qty`, `details`, `free_shipping`, `attachment`, `created_at`, `updated_at`, `status`, `featured_status`, `meta_title`, `meta_description`, `meta_image`, `request_status`, `denied_note`, `shipping_cost`, `multiply_qty`, `temp_shipping_cost`, `is_shipping_cost_updated`, `code`) VALUES
(1, 'admin', 1, 'Aluminium Pocket', 'aluminium-pocket-9dYcyr', 'physical', '[{\"id\":\"1\",\"position\":1}]', '1', NULL, NULL, 1, 'kg', 1, 1, NULL, '', NULL, '[{\"image_name\":\"2024-08-09-66b6327ecef5b.webp\",\"storage\":\"public\"}]', '[]', '2024-08-09-66b6327ef1fbd.webp', 'public', '1', NULL, 'youtube', NULL, '[]', 0, 'null', '[]', '[]', '[]', '[]', 0, 1200, 0, '0', 'percent', 'include', '1000', 'flat', 12331, 1, NULL, 0, NULL, '2024-08-09 15:15:10', '2024-08-15 16:11:13', 1, 1, 'Aluminium Pocket', NULL, NULL, 1, NULL, 0.00, 0, NULL, NULL, 'R1ADI5'),
(2, 'admin', 1, 'قميص موسمي', 'kmys-mosmy-u7PLtN', 'physical', '[{\"id\":\"4\",\"position\":1}]', '4', NULL, NULL, 1, 'kg', 1, 1, NULL, '', NULL, '[{\"image_name\":\"2024-08-09-66b63545c2285.webp\",\"storage\":\"public\"}]', '[]', '2024-08-09-66b63545e8ce4.webp', 'public', '1', NULL, 'youtube', NULL, '[]', 0, 'null', '[]', '[]', '[]', '[]', 0, 2900, 0, '0', 'percent', 'include', '0', 'flat', 0, 1, '<p><span class=\"text-gray-600\"></span></p><div class=\"p-4 prose dark:prose-invert\"><p><img src=\"https://feeef-s3.s3.eu-west-3.amazonaws.com/u/i76kodivlrwg/uploads/any/z02f44rahrm3.png\" alt=\"\"></p>\r\n<h2>المقدمة:</h2>\r\n<p>الهودي الخفيف هو قطعة أساسية في خزانة الملابس لأنها تجمع بين الراحة والأن</p></div>', 0, NULL, '2024-08-09 15:27:01', '2024-08-15 13:57:39', 1, 1, 'قميص موسمي', 'الهودي الخفيف هو قطعة أساسية في خزانة الملابس لأنها تجمع بين الراحة والأن', NULL, 1, NULL, 0.00, 0, NULL, NULL, 'KDF3D2'),
(3, 'seller', 1, 'Shoes', 'shoes-jqbG7c', 'physical', '[{\"id\":\"1\",\"position\":1}]', '1', NULL, NULL, 1, 'kg', 1, 1, NULL, '', NULL, '[{\"image_name\":\"2024-08-11-66b92f91d55d3.webp\",\"storage\":\"public\"}]', '[]', '2024-08-11-66b92f9225b5b.webp', 'public', '1', NULL, 'youtube', NULL, '[]', 0, 'null', '[]', '[]', '[]', '[]', 0, 1200, 0, '0', 'percent', 'include', '1000', 'flat', 19, 1, '<p>dd</p>', 0, NULL, '2024-08-11 21:39:30', '2024-08-15 23:05:21', 1, 1, 'Shoes', 'dd', NULL, 1, NULL, 0.00, 0, NULL, NULL, 'QRD14E'),
(4, 'admin', 1, 'Men\'s Fleece Lined Hoodie With Bear Print And Drawstring', 'mens-fleece-lined-hoodie-with-bear-print-and-drawstring-ydksHd', 'physical', '[{\"id\":\"1\",\"position\":1}]', '1', NULL, NULL, 1, 'kg', 1, 1, NULL, '', NULL, '[\"2024-08-15-66be2762e5c63.webp\",\"2024-08-15-66be27630d9b4.webp\",{\"image_name\":\"2024-08-15-66be27632860a.webp\",\"storage\":\"public\"}]', '[{\"color\":\"A52A2A\",\"image_name\":\"2024-08-15-66be2762e5c63.webp\",\"storage\":\"public\"},{\"color\":\"000000\",\"image_name\":\"2024-08-15-66be27630d9b4.webp\",\"storage\":\"public\"},{\"color\":null,\"image_name\":\"2024-08-15-66be27632860a.webp\",\"storage\":\"public\"}]', '2024-08-15-66be2763430c7.webp', 'public', '1', NULL, 'youtube', NULL, '[\"#A52A2A\",\"#000000\"]', 0, '[\"1\"]', '[{\"name\":\"choice_1\",\"title\":\"Sizes\",\"options\":[\"M\",\"L\",\"XL\",\"S\"]}]', '[{\"type\":\"Brown-M\",\"price\":1000,\"sku\":\"-BROWN-M\",\"qty\":7},{\"type\":\"Brown-L\",\"price\":1000,\"sku\":\"-BROWN-L\",\"qty\":0},{\"type\":\"Brown-XL\",\"price\":1000,\"sku\":\"-BROWN-XL\",\"qty\":10},{\"type\":\"Brown-S\",\"price\":1000,\"sku\":\"-BROWN-S\",\"qty\":0},{\"type\":\"Black-M\",\"price\":1000,\"sku\":\"-BLACK-M\",\"qty\":10},{\"type\":\"Black-L\",\"price\":1000,\"sku\":\"-BLACK-L\",\"qty\":10},{\"type\":\"Black-XL\",\"price\":1000,\"sku\":\"-BLACK-XL\",\"qty\":10},{\"type\":\"Black-S\",\"price\":1000,\"sku\":\"-BLACK-S\",\"qty\":10}]', '[]', '[]', 0, 1000, 0, '0', 'percent', 'include', '0', 'flat', 57, 1, '<div class=\"product-intro__description-table-item\" tabindex=\"0\" role=\"text\" style=\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\"><div class=\"key\" style=\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\">Details:</div><div class=\"val\" style=\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\">Drawstring<span dir=\"ltr\"></span></div></div><div class=\"product-intro__description-table-item\" tabindex=\"0\" role=\"text\" style=\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\"><div class=\"key\" style=\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\">Fit Type:</div><div class=\"val\" style=\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\">Loose<span dir=\"ltr\"></span></div></div><div class=\"product-intro__description-table-item\" tabindex=\"0\" role=\"text\" style=\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\"><div class=\"key\" style=\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\">Neckline:</div><div class=\"val\" style=\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\">Hooded<span dir=\"ltr\"></span></div></div><div class=\"product-intro__description-table-item\" tabindex=\"0\" role=\"text\" style=\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\"><div class=\"key\" style=\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\">Sleeve Type:</div><div class=\"val\" style=\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\">Drop Shoulder<span dir=\"ltr\"></span></div></div><div class=\"product-intro__description-table-item\" tabindex=\"0\" role=\"text\" style=\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\"><div class=\"key\" style=\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\">Style:</div><div class=\"val\" style=\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\">Casual<span dir=\"ltr\"></span></div></div><div class=\"product-intro__description-table-item\" tabindex=\"0\" role=\"text\" style=\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\"><div class=\"key\" style=\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\">Type:</div><div class=\"val\" style=\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\">Pullovers<span dir=\"ltr\"></span></div></div><div class=\"product-intro__description-table-item\" tabindex=\"0\" role=\"text\" style=\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\"><div class=\"key\" style=\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\">Color:</div><div class=\"val\" style=\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\">Burgundy<span dir=\"ltr\"></span></div></div><div class=\"product-intro__description-table-item\" tabindex=\"0\" role=\"text\" style=\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\"><div class=\"key\" style=\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\">Pattern Type:</div><div class=\"val\" style=\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\">Cartoon<span dir=\"ltr\"></span></div></div><div class=\"product-intro__description-table-item\" tabindex=\"0\" role=\"text\" style=\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\"><div class=\"key\" style=\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\">Sleeve Length:</div><div class=\"val\" style=\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\">Long Sleeve<span dir=\"ltr\"></span></div></div><div class=\"product-intro__description-table-item\" tabindex=\"0\" role=\"text\" style=\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\"><div class=\"key\" style=\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\">Length:</div><div class=\"val\" style=\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\">Regular<span dir=\"ltr\"></span></div></div><div class=\"product-intro__description-table-item\" tabindex=\"0\" role=\"text\" style=\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\"><div class=\"key\" style=\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\">Fabric:</div><div class=\"val\" style=\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\">Slight Stretch<span dir=\"ltr\"></span></div></div><div class=\"product-intro__description-table-item\" tabindex=\"0\" role=\"text\" style=\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\"><div class=\"key\" style=\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\">Material:</div><div class=\"val\" style=\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\">Fabric<span dir=\"ltr\"></span></div></div><div class=\"product-intro__description-table-item\" tabindex=\"0\" role=\"text\" style=\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\"><div class=\"key\" style=\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\">Composition:</div><div class=\"val\" style=\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\">100% Polyester<span dir=\"ltr\"></span></div></div><div class=\"product-intro__description-table-item\" tabindex=\"0\" role=\"text\" style=\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\"><div class=\"key\" style=\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\">Care Instructions:</div><div class=\"val\" style=\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\">Machine wash, do not dry clean<span dir=\"ltr\"></span></div></div><div class=\"product-intro__description-table-item\" tabindex=\"0\" role=\"text\" style=\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\"><div class=\"key\" style=\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\">Temperature:</div><div class=\"val\" style=\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\">Late Fall (10-17℃/50-63℉)<span dir=\"ltr\"></span></div></div><div class=\"product-intro__description-table-item\" tabindex=\"0\" role=\"text\" style=\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\"><div class=\"key\" style=\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\">Pockets:</div><div class=\"val\" style=\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\">No<span dir=\"ltr\"></span></div></div><div class=\"product-intro__description-table-item\" tabindex=\"0\" role=\"text\" style=\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\"><div class=\"key\" style=\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\">Lined For Added Warmth:</div><div class=\"val\" style=\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\">Yes<span dir=\"ltr\"></span></div></div><div class=\"product-intro__description-table-item\" tabindex=\"0\" role=\"text\" style=\"margin: 0px; padding: 0px; outline: 0px; color: rgb(34, 34, 34); display: table-row; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\"><div class=\"key\" style=\"margin: 0px; padding: 0px 25px 0px 0px; display: table-cell; vertical-align: bottom; width: 168.766px;\">Sheer:</div><div class=\"val\" style=\"margin: 0px; padding: 0px; display: table-cell; vertical-align: bottom;\">No</div></div>', 0, NULL, '2024-08-15 16:05:55', '2024-08-15 21:27:58', 1, 1, 'Men\'s Fleece Lined Hoodie With Bear Print And Drawstring', 'Details:DrawstringFit Type:LooseNeckline:HoodedSleeve Type:Drop ShoulderStyle:CasualType:PulloversColor:BurgundyPattern Type:CartoonSleeve Length:Long SleeveLen', NULL, 1, NULL, 0.00, 0, NULL, NULL, 'OOL9C0');

-- --------------------------------------------------------

--
-- Structure de la table `product_compares`
--

CREATE TABLE `product_compares` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL COMMENT 'customer_id',
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `product_seos`
--

CREATE TABLE `product_seos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `index` varchar(255) DEFAULT NULL,
  `no_follow` varchar(255) DEFAULT NULL,
  `no_image_index` varchar(255) DEFAULT NULL,
  `no_archive` varchar(255) DEFAULT NULL,
  `no_snippet` varchar(255) DEFAULT NULL,
  `max_snippet` varchar(255) DEFAULT NULL,
  `max_snippet_value` varchar(255) DEFAULT NULL,
  `max_video_preview` varchar(255) DEFAULT NULL,
  `max_video_preview_value` varchar(255) DEFAULT NULL,
  `max_image_preview` varchar(255) DEFAULT NULL,
  `max_image_preview_value` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `product_seos`
--

INSERT INTO `product_seos` (`id`, `product_id`, `title`, `description`, `index`, `no_follow`, `no_image_index`, `no_archive`, `no_snippet`, `max_snippet`, `max_snippet_value`, `max_video_preview`, `max_video_preview_value`, `max_image_preview`, `max_image_preview_value`, `image`, `created_at`, `updated_at`) VALUES
(1, 1, 'Aluminium Pocket', NULL, '', '', '', '', '0', '0', '-1', '0', '-1', '0', 'medium', '2024-08-09-66b6327f20d70.webp', '2024-08-09 15:15:11', '2024-08-09 15:15:11'),
(2, 2, 'قميص موسمي', 'الهودي الخفيف هو قطعة أساسية في خزانة الملابس لأنها تجمع بين الراحة والأن', '', '', '', '', '0', '0', '-1', '0', '-1', '0', 'large', '2024-08-09-66b6354617671.webp', '2024-08-09 15:27:02', '2024-08-09 15:27:02'),
(3, 3, 'Shoes', 'dd', '', '', '', '', '0', '0', '-1', '0', '-1', '0', 'large', '2024-08-11-66b92f92670b6.webp', '2024-08-11 21:39:30', '2024-08-11 21:39:30'),
(4, 4, 'Men\'s Fleece Lined Hoodie With Bear Print And Drawstring', 'Details:DrawstringFit Type:LooseNeckline:HoodedSleeve Type:Drop ShoulderStyle:CasualType:PulloversColor:BurgundyPattern Type:CartoonSleeve Length:Long SleeveLen', '', '', '', '', '0', '0', '-1', '0', '-1', '0', 'large', '2024-08-15-66be27635b7af.webp', '2024-08-15 16:05:55', '2024-08-15 16:05:55');

-- --------------------------------------------------------

--
-- Structure de la table `product_stocks`
--

CREATE TABLE `product_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `variant` varchar(255) DEFAULT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `qty` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `product_tag`
--

CREATE TABLE `product_tag` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `refund_requests`
--

CREATE TABLE `refund_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_details_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(191) NOT NULL,
  `approved_count` tinyint(4) NOT NULL DEFAULT 0,
  `denied_count` tinyint(4) NOT NULL DEFAULT 0,
  `amount` double(8,2) NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `refund_reason` longtext NOT NULL,
  `images` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `approved_note` longtext DEFAULT NULL,
  `rejected_note` longtext DEFAULT NULL,
  `payment_info` longtext DEFAULT NULL,
  `change_by` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `refund_statuses`
--

CREATE TABLE `refund_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `refund_request_id` bigint(20) UNSIGNED DEFAULT NULL,
  `change_by` varchar(191) DEFAULT NULL,
  `change_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(191) DEFAULT NULL,
  `message` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `refund_transactions`
--

CREATE TABLE `refund_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_for` varchar(191) DEFAULT NULL,
  `payer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_receiver_id` bigint(20) UNSIGNED DEFAULT NULL,
  `paid_by` varchar(191) DEFAULT NULL,
  `paid_to` varchar(191) DEFAULT NULL,
  `payment_method` varchar(191) DEFAULT NULL,
  `payment_status` varchar(191) DEFAULT NULL,
  `amount` double(8,2) DEFAULT NULL,
  `transaction_type` varchar(191) DEFAULT NULL,
  `order_details_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `refund_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `customer_id` bigint(20) NOT NULL,
  `delivery_man_id` bigint(20) DEFAULT NULL,
  `order_id` bigint(20) DEFAULT NULL,
  `comment` mediumtext DEFAULT NULL,
  `attachment` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachment`)),
  `rating` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `is_saved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `review_replies`
--

CREATE TABLE `review_replies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `review_id` int(11) NOT NULL,
  `added_by_id` int(11) DEFAULT NULL,
  `added_by` varchar(255) NOT NULL COMMENT 'customer, seller, admin, deliveryman',
  `reply_text` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `robots_meta_contents`
--

CREATE TABLE `robots_meta_contents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `page_title` varchar(255) DEFAULT NULL,
  `page_name` varchar(255) DEFAULT NULL,
  `page_url` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_image` varchar(255) DEFAULT NULL,
  `canonicals_url` varchar(255) DEFAULT NULL,
  `index` varchar(255) DEFAULT NULL,
  `no_follow` varchar(255) DEFAULT NULL,
  `no_image_index` varchar(255) DEFAULT NULL,
  `no_archive` varchar(255) DEFAULT NULL,
  `no_snippet` varchar(255) DEFAULT NULL,
  `max_snippet` varchar(255) DEFAULT NULL,
  `max_snippet_value` varchar(255) DEFAULT NULL,
  `max_video_preview` varchar(255) DEFAULT NULL,
  `max_video_preview_value` varchar(255) DEFAULT NULL,
  `max_image_preview` varchar(255) DEFAULT NULL,
  `max_image_preview_value` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `search_functions`
--

CREATE TABLE `search_functions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(150) DEFAULT NULL,
  `url` varchar(250) DEFAULT NULL,
  `visible_for` varchar(191) NOT NULL DEFAULT 'admin',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `search_functions`
--

INSERT INTO `search_functions` (`id`, `key`, `url`, `visible_for`, `created_at`, `updated_at`) VALUES
(1, 'Dashboard', 'admin/dashboard', 'admin', NULL, NULL),
(2, 'Order All', 'admin/orders/list/all', 'admin', NULL, NULL),
(3, 'Order Pending', 'admin/orders/list/pending', 'admin', NULL, NULL),
(4, 'Order Processed', 'admin/orders/list/processed', 'admin', NULL, NULL),
(5, 'Order Delivered', 'admin/orders/list/delivered', 'admin', NULL, NULL),
(6, 'Order Returned', 'admin/orders/list/returned', 'admin', NULL, NULL),
(7, 'Order Failed', 'admin/orders/list/failed', 'admin', NULL, NULL),
(8, 'Brand Add', 'admin/brand/add-new', 'admin', NULL, NULL),
(9, 'Brand List', 'admin/brand/list', 'admin', NULL, NULL),
(10, 'Banner', 'admin/banner/list', 'admin', NULL, NULL),
(11, 'Category', 'admin/category/view', 'admin', NULL, NULL),
(12, 'Sub Category', 'admin/category/sub-category/view', 'admin', NULL, NULL),
(13, 'Sub sub category', 'admin/category/sub-sub-category/view', 'admin', NULL, NULL),
(14, 'Attribute', 'admin/attribute/view', 'admin', NULL, NULL),
(15, 'Product', 'admin/product/list', 'admin', NULL, NULL),
(16, 'Promotion', 'admin/coupon/add-new', 'admin', NULL, NULL),
(17, 'Custom Role', 'admin/custom-role/create', 'admin', NULL, NULL),
(18, 'Employee', 'admin/employee/add-new', 'admin', NULL, NULL),
(19, 'Seller', 'admin/sellers/seller-list', 'admin', NULL, NULL),
(20, 'Contacts', 'admin/contact/list', 'admin', NULL, NULL),
(21, 'Flash Deal', 'admin/deal/flash', 'admin', NULL, NULL),
(22, 'Deal of the day', 'admin/deal/day', 'admin', NULL, NULL),
(23, 'Language', 'admin/business-settings/language', 'admin', NULL, NULL),
(24, 'Mail', 'admin/business-settings/mail', 'admin', NULL, NULL),
(25, 'Shipping method', 'admin/business-settings/shipping-method/add', 'admin', NULL, NULL),
(26, 'Currency', 'admin/currency/view', 'admin', NULL, NULL),
(27, 'Payment method', 'admin/business-settings/payment-method', 'admin', NULL, NULL),
(28, 'SMS Gateway', 'admin/business-settings/sms-gateway', 'admin', NULL, NULL),
(29, 'Support Ticket', 'admin/support-ticket/view', 'admin', NULL, NULL),
(30, 'FAQ', 'admin/helpTopic/list', 'admin', NULL, NULL),
(31, 'About Us', 'admin/business-settings/about-us', 'admin', NULL, NULL),
(32, 'Terms and Conditions', 'admin/business-settings/terms-condition', 'admin', NULL, NULL),
(33, 'Web Config', 'admin/business-settings/web-config', 'admin', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `sellers`
--

CREATE TABLE `sellers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `f_name` varchar(30) DEFAULT NULL,
  `l_name` varchar(30) DEFAULT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `image` varchar(30) NOT NULL DEFAULT 'def.png',
  `email` varchar(80) NOT NULL,
  `password` varchar(80) DEFAULT NULL,
  `status` varchar(15) NOT NULL DEFAULT 'pending',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `bank_name` varchar(191) DEFAULT NULL,
  `branch` varchar(191) DEFAULT NULL,
  `account_no` varchar(191) DEFAULT NULL,
  `holder_name` varchar(191) DEFAULT NULL,
  `auth_token` text DEFAULT NULL,
  `sales_commission_percentage` double(8,2) DEFAULT NULL,
  `gst` varchar(191) DEFAULT NULL,
  `cm_firebase_token` varchar(191) DEFAULT NULL,
  `pos_status` tinyint(1) NOT NULL DEFAULT 0,
  `minimum_order_amount` double(8,2) NOT NULL DEFAULT 0.00,
  `free_delivery_status` int(11) NOT NULL DEFAULT 0,
  `free_delivery_over_amount` double(8,2) NOT NULL DEFAULT 0.00,
  `app_language` varchar(191) NOT NULL DEFAULT 'en'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sellers`
--

INSERT INTO `sellers` (`id`, `f_name`, `l_name`, `phone`, `image`, `email`, `password`, `status`, `remember_token`, `created_at`, `updated_at`, `bank_name`, `branch`, `account_no`, `holder_name`, `auth_token`, `sales_commission_percentage`, `gst`, `cm_firebase_token`, `pos_status`, `minimum_order_amount`, `free_delivery_status`, `free_delivery_over_amount`, `app_language`) VALUES
(1, 'mahdi', 'harzallah', '+2130562380150', '2024-08-11-66b92ecc9f877.webp', 'mahdiharzallah21@gmail.com', '$2y$10$vLFvbpsizSkHdBDye2ZJKOhysP8V6onAChWRoIMvUL40sry55LPnS', 'approved', NULL, '2024-08-11 21:36:12', '2024-08-11 21:37:48', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0.00, 0, 0.00, 'en'),
(2, 'adem', 'julian', '+2130549330223', '2024-08-16-66bfcb03ad997.webp', 'mahdiharzallah11@gmail.com', '$2y$10$IXwt2vniwXxpjyAsIfrhaOhaWeQ86Zmd3A6vO0JIZm/biUXKNMALO', 'approved', NULL, '2024-08-16 21:56:19', '2024-08-16 21:57:19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0.00, 0, 0.00, 'en');

-- --------------------------------------------------------

--
-- Structure de la table `seller_wallets`
--

CREATE TABLE `seller_wallets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `seller_id` bigint(20) DEFAULT NULL,
  `total_earning` double NOT NULL DEFAULT 0,
  `withdrawn` double NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `commission_given` double(8,2) NOT NULL DEFAULT 0.00,
  `pending_withdraw` double(8,2) NOT NULL DEFAULT 0.00,
  `delivery_charge_earned` double(8,2) NOT NULL DEFAULT 0.00,
  `collected_cash` double(8,2) NOT NULL DEFAULT 0.00,
  `total_tax_collected` double(8,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `seller_wallets`
--

INSERT INTO `seller_wallets` (`id`, `seller_id`, `total_earning`, `withdrawn`, `created_at`, `updated_at`, `commission_given`, `pending_withdraw`, `delivery_charge_earned`, `collected_cash`, `total_tax_collected`) VALUES
(1, 1, 0, 0, '2024-08-11 21:36:12', '2024-08-11 21:36:12', 0.00, 0.00, 0.00, 0.00, 0.00),
(2, 2, 0, 0, '2024-08-16 21:56:19', '2024-08-16 21:56:19', 0.00, 0.00, 0.00, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Structure de la table `seller_wallet_histories`
--

CREATE TABLE `seller_wallet_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `seller_id` bigint(20) DEFAULT NULL,
  `amount` double NOT NULL DEFAULT 0,
  `order_id` bigint(20) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `payment` varchar(191) NOT NULL DEFAULT 'received',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `shipping_addresses`
--

CREATE TABLE `shipping_addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` varchar(15) DEFAULT NULL,
  `is_guest` tinyint(4) NOT NULL DEFAULT 0,
  `contact_person_name` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address_type` varchar(20) NOT NULL DEFAULT 'home',
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `state` varchar(191) DEFAULT NULL,
  `country` varchar(191) DEFAULT NULL,
  `latitude` varchar(191) DEFAULT NULL,
  `longitude` varchar(191) DEFAULT NULL,
  `is_billing` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `shipping_addresses`
--

INSERT INTO `shipping_addresses` (`id`, `customer_id`, `is_guest`, `contact_person_name`, `email`, `address_type`, `address`, `city`, `zip`, `phone`, `created_at`, `updated_at`, `state`, `country`, `latitude`, `longitude`, `is_billing`) VALUES
(1, '33', 0, '591136162 591136162', NULL, 'home', 'Lgerhgff', 'Blida', '200', '591136162', '2024-08-13 07:52:12', '2024-08-13 07:52:12', NULL, 'الجزائر', '36.516009377528775', '2.892129085958004', 0),
(2, '33', 0, '591136162 591136162', NULL, 'home', 'Ouled Yaicch', 'Blida', '2001', '591136162', '2024-08-13 11:24:29', '2024-08-13 11:24:29', NULL, 'الجزائر', '36.51598297061278', '2.8921716660261154', 0),
(3, '33', 0, '591136162', '591136162@gmail.com', 'home', 'تيمقتن', 'وادى الشولى', '13011', '591136162', '2024-08-15 21:17:19', '2024-08-15 21:17:19', NULL, 'الجزائر', '-1.1420878', '34.8666661', 0);

-- --------------------------------------------------------

--
-- Structure de la table `shipping_methods`
--

CREATE TABLE `shipping_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `creator_id` bigint(20) DEFAULT NULL,
  `creator_type` varchar(191) NOT NULL DEFAULT 'admin',
  `title` varchar(100) DEFAULT NULL,
  `cost` decimal(8,2) NOT NULL DEFAULT 0.00,
  `duration` varchar(20) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `shipping_methods`
--

INSERT INTO `shipping_methods` (`id`, `creator_id`, `creator_type`, `title`, `cost`, `duration`, `status`, `created_at`, `updated_at`) VALUES
(2, 1, 'admin', 'Company Vehicle', 5.00, '2 Week', 1, '2021-05-25 20:57:04', '2021-05-25 20:57:04');

-- --------------------------------------------------------

--
-- Structure de la table `shipping_types`
--

CREATE TABLE `shipping_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `seller_id` bigint(20) UNSIGNED DEFAULT NULL,
  `shipping_type` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `shipping_types`
--

INSERT INTO `shipping_types` (`id`, `seller_id`, `shipping_type`, `created_at`, `updated_at`) VALUES
(1, 0, 'order_wise', '2024-08-08 03:41:30', '2024-08-08 03:41:30');

-- --------------------------------------------------------

--
-- Structure de la table `shops`
--

CREATE TABLE `shops` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `seller_id` bigint(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(191) NOT NULL DEFAULT 'en',
  `address` varchar(255) NOT NULL,
  `contact` varchar(25) NOT NULL,
  `image` varchar(30) NOT NULL DEFAULT 'def.png',
  `image_storage_type` varchar(10) DEFAULT 'public',
  `bottom_banner` varchar(191) DEFAULT NULL,
  `bottom_banner_storage_type` varchar(10) DEFAULT 'public',
  `offer_banner` varchar(255) DEFAULT NULL,
  `offer_banner_storage_type` varchar(10) DEFAULT 'public',
  `vacation_start_date` date DEFAULT NULL,
  `vacation_end_date` date DEFAULT NULL,
  `vacation_note` varchar(255) DEFAULT NULL,
  `vacation_status` tinyint(4) NOT NULL DEFAULT 0,
  `temporary_close` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `banner` varchar(191) NOT NULL,
  `banner_storage_type` varchar(10) DEFAULT 'public'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `shops`
--

INSERT INTO `shops` (`id`, `seller_id`, `name`, `slug`, `address`, `contact`, `image`, `image_storage_type`, `bottom_banner`, `bottom_banner_storage_type`, `offer_banner`, `offer_banner_storage_type`, `vacation_start_date`, `vacation_end_date`, `vacation_note`, `vacation_status`, `temporary_close`, `created_at`, `updated_at`, `banner`, `banner_storage_type`) VALUES
(1, 1, 'GameHub', 'gamehub-RYGQFI', 'Oulad Yaiche blida\r\nBlida Mozaya', '+2130562380150', '2024-08-11-66b92ecccdf94.webp', 'public', 'def.png', 'public', NULL, 'public', NULL, NULL, NULL, 0, 0, '2024-08-11 21:36:12', '2024-08-11 21:36:12', '2024-08-11-66b92eccef65c.webp', 'public'),
(2, 2, 'feeef', 'feeef-qiivZ3', 'Oulad Yaiche blida\r\nBlida Mozaya', '+2130549330223', '2024-08-16-66bfcb03c3d79.webp', 'public', 'def.png', 'public', NULL, 'public', NULL, NULL, NULL, 0, 0, '2024-08-16 21:56:19', '2024-08-16 21:56:19', '2024-08-16-66bfcb03d3cd7.webp', 'public');

-- --------------------------------------------------------

--
-- Structure de la table `shop_followers`
--

CREATE TABLE `shop_followers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shop_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Customer ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `social_medias`
--

CREATE TABLE `social_medias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `link` varchar(100) NOT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `active_status` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `social_medias`
--

INSERT INTO `social_medias` (`id`, `name`, `link`, `icon`, `active_status`, `status`, `created_at`, `updated_at`) VALUES
(1, 'twitter', 'https://www.w3schools.com/howto/howto_css_table_responsive.asp', 'fa fa-twitter', 1, 1, '2020-12-31 21:18:03', '2020-12-31 21:18:25'),
(2, 'linkedin', 'https://dev.6amtech.com/', 'fa fa-linkedin', 1, 1, '2021-02-27 16:23:01', '2021-02-27 16:23:05'),
(3, 'google-plus', 'https://dev.6amtech.com/', 'fa fa-google-plus-square', 1, 1, '2021-02-27 16:23:30', '2021-02-27 16:23:33'),
(4, 'pinterest', 'https://dev.6amtech.com/', 'fa fa-pinterest', 1, 1, '2021-02-27 16:24:14', '2021-02-27 16:24:26'),
(5, 'instagram', 'https://dev.6amtech.com/', 'fa fa-instagram', 1, 1, '2021-02-27 16:24:36', '2021-02-27 16:24:41'),
(6, 'facebook', 'facebook.com', 'fa fa-facebook', 1, 1, '2021-02-27 19:19:42', '2021-06-11 17:41:59');

-- --------------------------------------------------------

--
-- Structure de la table `soft_credentials`
--

CREATE TABLE `soft_credentials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(191) DEFAULT NULL,
  `value` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `storages`
--

CREATE TABLE `storages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `data_type` varchar(255) NOT NULL,
  `data_id` varchar(100) NOT NULL,
  `key` varchar(255) DEFAULT NULL,
  `value` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `storages`
--

INSERT INTO `storages` (`id`, `data_type`, `data_id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\ProductSeo', '1', 'image', 'public', '2024-08-09 15:15:11', '2024-08-09 15:15:11'),
(2, 'App\\Models\\ProductSeo', '2', 'image', 'public', '2024-08-09 15:27:02', '2024-08-09 15:27:02'),
(3, 'App\\Models\\Seller', '1', 'image', 'public', '2024-08-11 21:36:12', '2024-08-11 21:36:12'),
(4, 'App\\Models\\ProductSeo', '3', 'image', 'public', '2024-08-11 21:39:30', '2024-08-11 21:39:30'),
(5, 'App\\Models\\Banner', '1', 'photo', 'public', '2024-08-15 13:59:51', '2024-08-15 13:59:51'),
(6, 'App\\Models\\Banner', '2', 'photo', 'public', '2024-08-15 15:35:50', '2024-08-15 15:35:50'),
(7, 'App\\Models\\ProductSeo', '4', 'image', 'public', '2024-08-15 16:05:55', '2024-08-15 16:05:55'),
(8, 'App\\Models\\Seller', '2', 'image', 'public', '2024-08-16 21:56:19', '2024-08-16 21:56:19');

-- --------------------------------------------------------

--
-- Structure de la table `store_user`
--

CREATE TABLE `store_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `store_id` bigint(20) UNSIGNED NOT NULL,
  `points` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `store_user`
--

INSERT INTO `store_user` (`id`, `user_id`, `store_id`, `points`, `created_at`, `updated_at`) VALUES
(1, 35, 1, 17, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) DEFAULT NULL,
  `subject` varchar(150) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `priority` varchar(15) NOT NULL DEFAULT 'low',
  `description` varchar(255) DEFAULT NULL,
  `attachment` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachment`)),
  `reply` varchar(255) DEFAULT NULL,
  `status` varchar(15) NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `support_ticket_convs`
--

CREATE TABLE `support_ticket_convs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `support_ticket_id` bigint(20) DEFAULT NULL,
  `admin_id` bigint(20) DEFAULT NULL,
  `customer_message` varchar(191) DEFAULT NULL,
  `attachment` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachment`)),
  `admin_message` varchar(191) DEFAULT NULL,
  `position` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tags`
--

CREATE TABLE `tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tag` varchar(191) NOT NULL,
  `visit_count` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` bigint(20) DEFAULT NULL,
  `payment_for` varchar(100) DEFAULT NULL,
  `payer_id` bigint(20) DEFAULT NULL,
  `payment_receiver_id` bigint(20) DEFAULT NULL,
  `paid_by` varchar(15) DEFAULT NULL,
  `paid_to` varchar(15) DEFAULT NULL,
  `payment_method` varchar(15) DEFAULT NULL,
  `payment_status` varchar(10) NOT NULL DEFAULT 'success',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `amount` double(8,2) NOT NULL DEFAULT 0.00,
  `transaction_type` varchar(191) DEFAULT NULL,
  `order_details_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `translations`
--

CREATE TABLE `translations` (
  `translationable_type` varchar(191) NOT NULL,
  `translationable_id` bigint(20) UNSIGNED NOT NULL,
  `locale` varchar(191) NOT NULL,
  `key` varchar(191) DEFAULT NULL,
  `value` text DEFAULT NULL,
  `id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(80) DEFAULT NULL,
  `f_name` varchar(255) DEFAULT NULL,
  `l_name` varchar(255) DEFAULT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `image` varchar(30) NOT NULL DEFAULT 'def.png',
  `email` varchar(80) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(80) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `street_address` varchar(250) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `zip` varchar(20) DEFAULT NULL,
  `house_no` varchar(50) DEFAULT NULL,
  `apartment_no` varchar(50) DEFAULT NULL,
  `cm_firebase_token` varchar(191) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `payment_card_last_four` varchar(191) DEFAULT NULL,
  `payment_card_brand` varchar(191) DEFAULT NULL,
  `payment_card_fawry_token` text DEFAULT NULL,
  `login_medium` varchar(191) DEFAULT NULL,
  `social_id` varchar(191) DEFAULT NULL,
  `is_phone_verified` tinyint(1) NOT NULL DEFAULT 0,
  `temporary_token` varchar(191) DEFAULT NULL,
  `is_email_verified` tinyint(1) NOT NULL DEFAULT 0,
  `wallet_balance` double(8,2) DEFAULT NULL,
  `loyalty_point` double(8,2) DEFAULT NULL,
  `login_hit_count` tinyint(4) NOT NULL DEFAULT 0,
  `is_temp_blocked` tinyint(1) NOT NULL DEFAULT 0,
  `temp_block_time` timestamp NULL DEFAULT NULL,
  `referral_code` varchar(255) DEFAULT NULL,
  `referred_by` int(11) DEFAULT NULL,
  `app_language` varchar(191) NOT NULL DEFAULT 'en'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `f_name`, `l_name`, `phone`, `image`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `street_address`, `country`, `city`, `zip`, `house_no`, `apartment_no`, `cm_firebase_token`, `is_active`, `payment_card_last_four`, `payment_card_brand`, `payment_card_fawry_token`, `login_medium`, `social_id`, `is_phone_verified`, `temporary_token`, `is_email_verified`, `wallet_balance`, `loyalty_point`, `login_hit_count`, `is_temp_blocked`, `temp_block_time`, `referral_code`, `referred_by`, `app_language`) VALUES
(0, 'walking customer', 'walking', 'customer', '000000000000', 'def.png', 'walking@customer.com', NULL, '', NULL, NULL, '2022-02-03 03:46:01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, 0, 0, NULL, NULL, NULL, 'en'),
(2, NULL, NULL, NULL, '', 'def.png', '', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, 0, 0, NULL, '123465789', NULL, 'en'),
(20, NULL, NULL, NULL, '', 'def.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, 0, 0, NULL, '1234657890', NULL, 'en'),
(21, NULL, NULL, NULL, NULL, 'def.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, 0, 0, NULL, '8552258', NULL, 'en'),
(22, NULL, NULL, NULL, NULL, 'def.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, 0, 0, NULL, '8552258f', NULL, 'en'),
(23, NULL, 'test', 'trest', '+213656765654', 'def.png', 'mohamadlounnas@gmail.com', NULL, '$2y$10$ngokRaV9evnpXgs.3ZduquQI7T2VexQFRMuIvdz7uuA3K7T/Xx7sO', NULL, '2024-08-08 01:43:57', '2024-08-08 01:43:57', NULL, NULL, NULL, NULL, NULL, NULL, 'cIrLUPpARtyinJGXjVjLGl:APA91bE8GeBlgKZg_ML2JGBigDYVMvYn0ICDXBipnLwJR8aCkfdE8YH6E8xlz2xh2QYFoK-sULujhEk6qOGNXgL44rkHnFOp5KNjw3_uw4cbuQAog4f6JR7Y4jhQxG4zeD9LzAj04IBq', 1, NULL, NULL, NULL, NULL, NULL, 0, 'FaReZorQCQkJbUi6rMw3zkSnONUoGsRHsWOZqSeg', 0, NULL, NULL, 0, 0, NULL, 'HHCPGVKUTW9V3FY4ZIS8', NULL, 'en'),
(24, NULL, 'mohamed', 'lounnas', '662186998', 'def.png', '662186998@gmail.com', NULL, '$2y$10$LQVt2.1575/6CgW85SgGxuPce9bDyI0p.qyWn8KaiwYwrxM.9kQ1m', NULL, '2024-08-08 02:17:28', '2024-08-08 02:18:46', NULL, NULL, NULL, NULL, NULL, NULL, 'cIrLUPpARtyinJGXjVjLGl:APA91bE8GeBlgKZg_ML2JGBigDYVMvYn0ICDXBipnLwJR8aCkfdE8YH6E8xlz2xh2QYFoK-sULujhEk6qOGNXgL44rkHnFOp5KNjw3_uw4cbuQAog4f6JR7Y4jhQxG4zeD9LzAj04IBq', 1, NULL, NULL, NULL, NULL, NULL, 0, 'BbhDbkDm7EQZT0W3ovTRZUNNMXxEjpNqDSjNnlE7', 0, NULL, NULL, 0, 0, NULL, 'QGWB8JQHGRXKHXUE114W', NULL, 'en'),
(25, NULL, 'mohamed', 'player', '512471663', 'def.png', '512471663@gmail.com', NULL, '$2y$10$72XjvpgAbHpWf51fAvn8Nu14DKbwl6KNlIsceq/HcUkCj/JhJRDMK', NULL, '2024-08-08 02:22:28', '2024-08-08 02:51:06', NULL, NULL, NULL, NULL, NULL, NULL, 'cIrLUPpARtyinJGXjVjLGl:APA91bE8GeBlgKZg_ML2JGBigDYVMvYn0ICDXBipnLwJR8aCkfdE8YH6E8xlz2xh2QYFoK-sULujhEk6qOGNXgL44rkHnFOp5KNjw3_uw4cbuQAog4f6JR7Y4jhQxG4zeD9LzAj04IBq', 1, NULL, NULL, NULL, NULL, NULL, 0, 'TfmTDTtSu12l5rwhHkJWj0XdY5TJdGR8BSLDizMH', 0, NULL, NULL, 0, 0, NULL, 'MVQQOACZVTERKOXINZDF', NULL, 'sa'),
(26, NULL, NULL, NULL, NULL, 'def.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, 0, 0, NULL, '8552258frr', NULL, 'en'),
(27, NULL, NULL, NULL, NULL, 'def.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, 0, 0, NULL, '8552258frrd', NULL, 'en'),
(28, NULL, NULL, NULL, NULL, 'def.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, 0, 0, NULL, '8552258frrdd', NULL, 'en'),
(29, NULL, NULL, NULL, NULL, 'def.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, 0, 0, NULL, '8552258f55', NULL, 'en'),
(30, NULL, NULL, NULL, NULL, 'def.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, 0, 0, NULL, '8552258f55d', NULL, 'en'),
(31, NULL, NULL, NULL, NULL, 'def.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, 0, 0, NULL, '8552258f558', NULL, 'en'),
(32, NULL, NULL, NULL, NULL, 'def.png', NULL, NULL, NULL, NULL, '2024-08-09 21:04:36', '2024-08-09 21:04:36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, 0, 0, NULL, '8552258f558f', NULL, 'en'),
(33, NULL, '591136162', '591136162', '591136162', 'def.png', '591136162@gmail.com', NULL, '$2y$10$nJnXbedoaMtgO0glNVx1DOGbA5QNb09cLdQyaSX9vTJhl5pv/b3aa', NULL, '2024-08-11 04:02:01', '2024-08-11 16:09:49', NULL, NULL, NULL, NULL, NULL, NULL, 'eA22ykssQUiO1oosRFU1Ep:APA91bG9ez11JOu8298jMW0RtgUqpN1hNcCWJ5-RK-PWgeINtUrDjDnEhv6_WeRrZ-_4mkqP_5taPheP9oA6E6dMYGyCFvwtk5j8eQiC5rmX-vRbmbRgPQ6NyKhrEGQWd3ZapjT9dvA2', 1, NULL, NULL, NULL, NULL, NULL, 0, 'W7uTawU95jvbp4oqElohQ7D6sALRCfqHnbpLr1L5', 0, NULL, NULL, 0, 0, NULL, 'EFYMUP6ARAJFQLRAVNDN', NULL, 'en'),
(34, 'youcef Fekhar', 'youcef', 'Fekhar', '+2130776833653', 'def.png', 'youceffekhar92@gmail.com', NULL, '$2y$10$Sq2lmZbgRBVwNrE0jDGBIehJ9W2Fgl4CR/Qv47oyX99BH6Z1HD93O', '4kRCUzzwwaOcYqy3YwMUrzDRqPqDpX5UGI9rNYjE9vcW5C2RdheoTJfoq5GW', '2024-08-11 15:39:14', '2024-08-11 15:40:12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, 0, 0, NULL, '1ILN2MTEPCHN3DIUS2BR', NULL, 'en'),
(35, 'mahdi harzallah', 'mahdi', 'harzallah', '+2130549330223', 'def.png', 'mahdiharzallah21@gmail.com', NULL, '$2y$10$EU9z.cTwNqSzLD3fiYjNCuEVCuUBPEDVBntH7MoYPNWZoASaRefWy', NULL, '2024-08-11 21:39:52', '2024-08-11 21:40:08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, 0, 0, NULL, 'SIAX7MY11LKBFFXXIE1L', NULL, 'en'),
(36, NULL, NULL, NULL, NULL, 'def.png', NULL, NULL, NULL, NULL, '2024-08-12 12:14:16', '2024-08-12 12:14:16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, 0, 0, NULL, '55s22s1d44d5s8', NULL, 'en');

-- --------------------------------------------------------

--
-- Structure de la table `vendor_registration_reasons`
--

CREATE TABLE `vendor_registration_reasons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `priority` tinyint(4) NOT NULL DEFAULT 1,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `vendor_registration_reasons`
--

INSERT INTO `vendor_registration_reasons` (`id`, `title`, `description`, `priority`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Millions of Users', 'Access a vast audience with millions of active users ready to buy your products.', 1, 1, NULL, NULL),
(2, 'Free Marketing', 'Benefit from our extensive, no-cost marketing efforts to boost your visibility and sales.', 2, 1, NULL, NULL),
(3, 'SEO Friendly', 'Enjoy enhanced search visibility with our SEO-friendly platform, driving more traffic to your listings.', 3, 1, NULL, NULL),
(4, '24/7 Support', 'Get round-the-clock support from our dedicated team to resolve any issues and assist you anytime.', 4, 1, NULL, NULL),
(5, 'Easy Onboarding', 'Start selling quickly with our user-friendly onboarding process designed to get you up and running fast.', 5, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `wallet_transactions`
--

CREATE TABLE `wallet_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `transaction_id` char(36) NOT NULL,
  `credit` decimal(24,3) NOT NULL DEFAULT 0.000,
  `debit` decimal(24,3) NOT NULL DEFAULT 0.000,
  `admin_bonus` decimal(24,3) NOT NULL DEFAULT 0.000,
  `balance` decimal(24,3) NOT NULL DEFAULT 0.000,
  `transaction_type` varchar(191) DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `reference` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `withdrawal_methods`
--

CREATE TABLE `withdrawal_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `method_name` varchar(191) NOT NULL,
  `method_fields` text NOT NULL,
  `is_default` tinyint(4) NOT NULL DEFAULT 0,
  `is_active` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `withdraw_requests`
--

CREATE TABLE `withdraw_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `seller_id` bigint(20) DEFAULT NULL,
  `delivery_man_id` bigint(20) DEFAULT NULL,
  `admin_id` bigint(20) DEFAULT NULL,
  `amount` varchar(191) NOT NULL DEFAULT '0.00',
  `withdrawal_method_id` bigint(20) UNSIGNED DEFAULT NULL,
  `withdrawal_method_fields` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `transaction_note` text DEFAULT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `addon_settings`
--
ALTER TABLE `addon_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_settings_id_index` (`id`);

--
-- Index pour la table `add_fund_bonus_categories`
--
ALTER TABLE `add_fund_bonus_categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Index pour la table `admin_roles`
--
ALTER TABLE `admin_roles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `admin_wallets`
--
ALTER TABLE `admin_wallets`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `admin_wallet_histories`
--
ALTER TABLE `admin_wallet_histories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `attributes`
--
ALTER TABLE `attributes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `billing_addresses`
--
ALTER TABLE `billing_addresses`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `business_settings`
--
ALTER TABLE `business_settings`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cart_shippings`
--
ALTER TABLE `cart_shippings`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `category_shipping_costs`
--
ALTER TABLE `category_shipping_costs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `chattings`
--
ALTER TABLE `chattings`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `customer_wallets`
--
ALTER TABLE `customer_wallets`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `customer_wallet_histories`
--
ALTER TABLE `customer_wallet_histories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `deal_of_the_days`
--
ALTER TABLE `deal_of_the_days`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `deliveryman_notifications`
--
ALTER TABLE `deliveryman_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `deliveryman_wallets`
--
ALTER TABLE `deliveryman_wallets`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `delivery_country_codes`
--
ALTER TABLE `delivery_country_codes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `delivery_histories`
--
ALTER TABLE `delivery_histories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `delivery_man_transactions`
--
ALTER TABLE `delivery_man_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `delivery_men`
--
ALTER TABLE `delivery_men`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `delivery_zip_codes`
--
ALTER TABLE `delivery_zip_codes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `digital_product_otp_verifications`
--
ALTER TABLE `digital_product_otp_verifications`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `digital_product_variations`
--
ALTER TABLE `digital_product_variations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `emergency_contacts`
--
ALTER TABLE `emergency_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `error_logs`
--
ALTER TABLE `error_logs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `feature_deals`
--
ALTER TABLE `feature_deals`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `flash_deals`
--
ALTER TABLE `flash_deals`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `flash_deal_products`
--
ALTER TABLE `flash_deal_products`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `guest_users`
--
ALTER TABLE `guest_users`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `help_topics`
--
ALTER TABLE `help_topics`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `loyalty_point_transactions`
--
ALTER TABLE `loyalty_point_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `most_demandeds`
--
ALTER TABLE `most_demandeds`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `notification_messages`
--
ALTER TABLE `notification_messages`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `notification_seens`
--
ALTER TABLE `notification_seens`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Index pour la table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Index pour la table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_personal_access_clients_client_id_index` (`client_id`);

--
-- Index pour la table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Index pour la table `offline_payments`
--
ALTER TABLE `offline_payments`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `offline_payment_methods`
--
ALTER TABLE `offline_payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `order_delivery_verifications`
--
ALTER TABLE `order_delivery_verifications`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `order_expected_delivery_histories`
--
ALTER TABLE `order_expected_delivery_histories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `order_status_histories`
--
ALTER TABLE `order_status_histories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `order_transactions`
--
ALTER TABLE `order_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `password_resets_email_index` (`identity`);

--
-- Index pour la table `paytabs_invoices`
--
ALTER TABLE `paytabs_invoices`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Index pour la table `phone_or_email_verifications`
--
ALTER TABLE `phone_or_email_verifications`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `product_compares`
--
ALTER TABLE `product_compares`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `product_seos`
--
ALTER TABLE `product_seos`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `product_stocks`
--
ALTER TABLE `product_stocks`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `product_tag`
--
ALTER TABLE `product_tag`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `refund_requests`
--
ALTER TABLE `refund_requests`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `refund_statuses`
--
ALTER TABLE `refund_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `refund_transactions`
--
ALTER TABLE `refund_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `review_replies`
--
ALTER TABLE `review_replies`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `robots_meta_contents`
--
ALTER TABLE `robots_meta_contents`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `search_functions`
--
ALTER TABLE `search_functions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `sellers`
--
ALTER TABLE `sellers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sellers_email_unique` (`email`);

--
-- Index pour la table `seller_wallets`
--
ALTER TABLE `seller_wallets`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `seller_wallet_histories`
--
ALTER TABLE `seller_wallet_histories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `shipping_methods`
--
ALTER TABLE `shipping_methods`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `shipping_types`
--
ALTER TABLE `shipping_types`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `shops`
--
ALTER TABLE `shops`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `shop_followers`
--
ALTER TABLE `shop_followers`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `social_medias`
--
ALTER TABLE `social_medias`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `soft_credentials`
--
ALTER TABLE `soft_credentials`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `storages`
--
ALTER TABLE `storages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `storages_data_id_index` (`data_id`),
  ADD KEY `storages_value_index` (`value`);

--
-- Index pour la table `store_user`
--
ALTER TABLE `store_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `store_user_user_id_foreign` (`user_id`),
  ADD KEY `store_user_store_id_foreign` (`store_id`);

--
-- Index pour la table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `support_ticket_convs`
--
ALTER TABLE `support_ticket_convs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `transactions`
--
ALTER TABLE `transactions`
  ADD UNIQUE KEY `transactions_id_unique` (`id`);

--
-- Index pour la table `translations`
--
ALTER TABLE `translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `translations_translationable_id_index` (`translationable_id`),
  ADD KEY `translations_locale_index` (`locale`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Index pour la table `vendor_registration_reasons`
--
ALTER TABLE `vendor_registration_reasons`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `withdrawal_methods`
--
ALTER TABLE `withdrawal_methods`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `withdraw_requests`
--
ALTER TABLE `withdraw_requests`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `add_fund_bonus_categories`
--
ALTER TABLE `add_fund_bonus_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `admin_roles`
--
ALTER TABLE `admin_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `admin_wallets`
--
ALTER TABLE `admin_wallets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `admin_wallet_histories`
--
ALTER TABLE `admin_wallet_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `attributes`
--
ALTER TABLE `attributes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `billing_addresses`
--
ALTER TABLE `billing_addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `business_settings`
--
ALTER TABLE `business_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=178;

--
-- AUTO_INCREMENT pour la table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `cart_shippings`
--
ALTER TABLE `cart_shippings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `category_shipping_costs`
--
ALTER TABLE `category_shipping_costs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `chattings`
--
ALTER TABLE `chattings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `colors`
--
ALTER TABLE `colors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT pour la table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `customer_wallets`
--
ALTER TABLE `customer_wallets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `customer_wallet_histories`
--
ALTER TABLE `customer_wallet_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `deal_of_the_days`
--
ALTER TABLE `deal_of_the_days`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `deliveryman_notifications`
--
ALTER TABLE `deliveryman_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `deliveryman_wallets`
--
ALTER TABLE `deliveryman_wallets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `delivery_country_codes`
--
ALTER TABLE `delivery_country_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `delivery_histories`
--
ALTER TABLE `delivery_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `delivery_man_transactions`
--
ALTER TABLE `delivery_man_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `delivery_men`
--
ALTER TABLE `delivery_men`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `delivery_zip_codes`
--
ALTER TABLE `delivery_zip_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `digital_product_otp_verifications`
--
ALTER TABLE `digital_product_otp_verifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `digital_product_variations`
--
ALTER TABLE `digital_product_variations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `emergency_contacts`
--
ALTER TABLE `emergency_contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `error_logs`
--
ALTER TABLE `error_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `feature_deals`
--
ALTER TABLE `feature_deals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `flash_deals`
--
ALTER TABLE `flash_deals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `flash_deal_products`
--
ALTER TABLE `flash_deal_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `guest_users`
--
ALTER TABLE `guest_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=209;

--
-- AUTO_INCREMENT pour la table `help_topics`
--
ALTER TABLE `help_topics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `loyalty_point_transactions`
--
ALTER TABLE `loyalty_point_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=277;

--
-- AUTO_INCREMENT pour la table `most_demandeds`
--
ALTER TABLE `most_demandeds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `notification_messages`
--
ALTER TABLE `notification_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT pour la table `notification_seens`
--
ALTER TABLE `notification_seens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `offline_payments`
--
ALTER TABLE `offline_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `offline_payment_methods`
--
ALTER TABLE `offline_payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100007;

--
-- AUTO_INCREMENT pour la table `order_delivery_verifications`
--
ALTER TABLE `order_delivery_verifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `order_expected_delivery_histories`
--
ALTER TABLE `order_expected_delivery_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `order_status_histories`
--
ALTER TABLE `order_status_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `order_transactions`
--
ALTER TABLE `order_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `paytabs_invoices`
--
ALTER TABLE `paytabs_invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `phone_or_email_verifications`
--
ALTER TABLE `phone_or_email_verifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `product_compares`
--
ALTER TABLE `product_compares`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `product_seos`
--
ALTER TABLE `product_seos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `product_stocks`
--
ALTER TABLE `product_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `product_tag`
--
ALTER TABLE `product_tag`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `refund_requests`
--
ALTER TABLE `refund_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `refund_statuses`
--
ALTER TABLE `refund_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `refund_transactions`
--
ALTER TABLE `refund_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `review_replies`
--
ALTER TABLE `review_replies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `robots_meta_contents`
--
ALTER TABLE `robots_meta_contents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `search_functions`
--
ALTER TABLE `search_functions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT pour la table `sellers`
--
ALTER TABLE `sellers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `seller_wallets`
--
ALTER TABLE `seller_wallets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `seller_wallet_histories`
--
ALTER TABLE `seller_wallet_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `shipping_methods`
--
ALTER TABLE `shipping_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `shipping_types`
--
ALTER TABLE `shipping_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `shops`
--
ALTER TABLE `shops`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `shop_followers`
--
ALTER TABLE `shop_followers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `social_medias`
--
ALTER TABLE `social_medias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `soft_credentials`
--
ALTER TABLE `soft_credentials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `storages`
--
ALTER TABLE `storages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `store_user`
--
ALTER TABLE `store_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `support_ticket_convs`
--
ALTER TABLE `support_ticket_convs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `translations`
--
ALTER TABLE `translations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pour la table `vendor_registration_reasons`
--
ALTER TABLE `vendor_registration_reasons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `withdrawal_methods`
--
ALTER TABLE `withdrawal_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `withdraw_requests`
--
ALTER TABLE `withdraw_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `store_user`
--
ALTER TABLE `store_user`
  ADD CONSTRAINT `store_user_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `store_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
