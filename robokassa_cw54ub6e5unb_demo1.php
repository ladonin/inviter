<?
  $mrh_login = "inviterbiz";
  $mrh_pass1 = "cT7SjoRah9pe521TRhRZ";
  $inv_id = 678678;
  $inv_desc = "Товары для животных";
  $out_summ = "100.00";
  $IsTest = 1;
  $crc = hash('sha256', "$mrh_login:$out_summ:$inv_id:$mrh_pass1");
  print "<html><script language=JavaScript ".
      "src='https://auth.robokassa.ru/Merchant/PaymentForm/FormMS.js?".
      "MerchantLogin=$mrh_login&OutSum=$out_summ&InvoiceID=$inv_id".
      "&Description=$inv_desc&SignatureValue=$crc&IsTest=$IsTest'></script></html>";
?>