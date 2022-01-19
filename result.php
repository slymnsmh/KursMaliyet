<?php

session_start();

if (empty($_SESSION['user_id'])) {
	header("location: index.php?from=userPage");
	exit();
}

$info = "";

if (isset($_POST['calculate'])) {
	$indirim_zam_sonrasi_b_fiyat = $_POST['valilik_b_taban'] + $_POST['tabana_ekle'] - $_POST['indirim_tutari'];

	$kac_b_disi = ceil($_POST['kac_b'] * $_POST['aylik_b_disi_orani']);

	$b_disi_aday_top_kar = $kac_b_disi * $_POST['b_disi_aday_ilave_kar_katkisi'];
	
	$top_bsrz_drk_saati = $_POST['kac_b'] * $_POST['bsrz_aday_orani'] * 2;
	$bsrz_ciro_katkisi = $top_bsrz_drk_saati * $_POST['bsrz_ozel_ders_ucret'];

	$top_keyfi_ozel_ders_saati = $_POST['kac_b'] * $_POST['keyfi_ozel_ders_alan_orani'] * $_POST['keyfi_ozel_ders_alan_ort_saat_sayisi'];
	$keyfi_ozel_ders_ciro_katkisi = $_POST['ozel_ders_ucret'] * $top_keyfi_ozel_ders_saati;

	$bsrz_keyfi_top_saat = $top_bsrz_drk_saati + $top_keyfi_ozel_ders_saati;
	$ek_ders_yakilan_yakit_tutari = $bsrz_keyfi_top_saat * $_POST['b_saate_kac_km'] * $_POST['bir_oto_kmde_kac_tl'];

	$asgari_ucretli_tazminat_maliyeti = $_POST['tazminata_esas_brut'] / 12;
	$asgari_ucretli_personel_sgk_dahil_maliyet = $_POST['asgari_ucretli_personel_brutu_sgk_haric'] + $_POST['asgari_ucretli_personel_sgk_maliyeti'];
	$asgari_ucretli_personel_aylik_maas_tazminat_maliyeti = $asgari_ucretli_tazminat_maliyeti + $asgari_ucretli_personel_sgk_dahil_maliyet;

	$kac_oto_lazim = ceil($_POST['kac_b'] / $_POST['bir_oto_kac_b']);
	$top_oto_sayisi = $kac_oto_lazim + $_POST['ihtiyac_disi_otomobil_sayisi'] <= $_POST['minimum_arac_sayisi'] ? $_POST['minimum_arac_sayisi'] : $kac_oto_lazim + $_POST['ihtiyac_disi_otomobil_sayisi'];

	$kac_sinif = ceil($_POST['kac_b'] / $_POST['sinif_kontenjani']);
	$b_hoca_ayda_max_ders = 200;

	$top_b_drk_calisma_saati = $_POST['kac_b'] * $_POST['her_b_kac_saat_drk'];
	
	$kac_b_hoca_lazim = ceil($top_b_drk_calisma_saati / $b_hoca_ayda_max_ders);

	$drk_hocalari_aylik_top_maas_maliyeti = ($top_b_drk_calisma_saati / $b_hoca_ayda_max_ders) * $asgari_ucretli_personel_aylik_maas_tazminat_maliyeti;

	$gelinmeyen_dersler_icin_maas_tasarrufu = $drk_hocalari_aylik_top_maas_maliyeti - ($drk_hocalari_aylik_top_maas_maliyeti * $_POST['aday_drk_derse_gelme_orani']);

	$drk_hoca_tasarruflu_maliyeti = $drk_hocalari_aylik_top_maas_maliyeti - $gelinmeyen_dersler_icin_maas_tasarrufu;

	$kisi_basi_aylik_yemek_gideri = $_POST['kisi_basi_yemek_gideri'] * 26;
	$kac_personel_yemek_yiyecek = $kac_b_hoca_lazim + 2;
	$aylik_top_yemek_gideri = $kisi_basi_aylik_yemek_gideri * $kac_personel_yemek_yiyecek;

	$teorik_hoca_bir_sinif_diger_gider = ($_POST['trafikci_toplam_ucret'] + $_POST['ilkyardimci_toplam_ucret']) * 0.495;

	$top_teorikci_maliyeti = $kac_sinif * ($_POST['trafikci_toplam_ucret'] + $_POST['ilkyardimci_toplam_ucret'] + $teorik_hoca_bir_sinif_diger_gider);

	$top_cayci_vs_gideri = $_POST['cayci_vs_sayisi'] * $asgari_ucretli_personel_aylik_maas_tazminat_maliyeti;

	$mudur_maliyet = $asgari_ucretli_personel_aylik_maas_tazminat_maliyeti + $_POST['mudur_farki'];

	$sekreter_maliyet = $asgari_ucretli_personel_aylik_maas_tazminat_maliyeti + $_POST['sekreter_farki'];

	$pist_bina_kira_stopaji = ($_POST['pist_kirasi'] + $_POST['bina_kirasi'] + $_POST['dinlenme_tesisi_kirasi']) * 0.25;

	$aylik_matbuu_gideri = $_POST['kac_b'] * $_POST['cemiyet_evrak_bedel'];

	$top_kitap_gideri = $_POST['kac_b'] * $_POST['kitap_birim_fiyat'];

	$e_sinav_aylik_gider = $_POST['e_sinav_yillik_aidat'] / 12;

	$aylik_top_icme_suyu_gideri = $_POST['damacana_su_fiyati'] * $_POST['ayda_kac_damacana'];

	$aylik_ort_dogalgaz_gideri = (($_POST['yaz_aylari_dogalgaz'] * 6) + ($_POST['kis_dogalgaz_gideri'] * 6)) / 12;

	$sozlesme_damga_vergisi = $indirim_zam_sonrasi_b_fiyat * $_POST['kac_b'] * 0.009;

	$b_egitimi_aylik_yakit_gideri = $_POST['kac_b'] * ($_POST['her_b_kac_saat_drk'] + $_POST['adayin_sinav_icin_verilen_hizmet_saati']) * $_POST['b_saate_kac_km'] * $_POST['bir_oto_kmde_kac_tl'];
	$ders_disi_yakit_gideri = ($kac_b_hoca_lazim + 2) * 12 * $_POST['b_saate_kac_km'] * 26;

	$aylik_top_yakit_gideri = $b_egitimi_aylik_yakit_gideri + $ders_disi_yakit_gideri;

	$aylik_fenni_egsoz_muayenesi_gideri = $_POST['oto_yillik_fenni_ve_egsoz'] / 12;

	$aylik_mtv_gideri = $_POST['oto_yillik_mtv'] / 12;

	$aylik_ort_trf_sig_gideri = $_POST['oto_yillik_trf_sig_gideri'] / 12;

	$aylik_kasko_gideri = $_POST['oto_yillik_kasko'] / 12;

	$aylik_oto_amortisman_gideri = $_POST['ort_oto_su_anki_fiyat'] / ($_POST['oto_aldiktan_kac_yil_sonra_sifir'] * 12);

	$bir_oto_aylik_top_gider = $aylik_fenni_egsoz_muayenesi_gideri + $aylik_mtv_gideri + $_POST['oto_aylik_bakim_vs_gideri'] + $aylik_ort_trf_sig_gideri + $aylik_kasko_gideri + $aylik_oto_amortisman_gideri;

	$tum_otolar_aylik_top_gider = $bir_oto_aylik_top_gider * $top_oto_sayisi;

	$top_ciro = ($_POST['kac_b'] * $indirim_zam_sonrasi_b_fiyat) + ($bsrz_ciro_katkisi + $keyfi_ozel_ders_ciro_katkisi);

	$gelinmeyen_dersler_icin_yakit_tasarrufu = $aylik_top_yakit_gideri - ($aylik_top_yakit_gideri * $_POST['aday_drk_derse_gelme_orani']);

	$tasarruflu_yakit_gideri = $aylik_top_yakit_gideri - $gelinmeyen_dersler_icin_yakit_tasarrufu;

	$kurum_ruhsat_harci = $_POST['yillik_kurum_harci'] / 12;

	$satistan_olusan_kdv = $top_ciro - ($top_ciro / 1.08);
	$alistan_kdv_matrahi = $_POST['ikram_gideri'] + $aylik_top_yemek_gideri + $_POST['sabit_telefon_gideri'] + $_POST['gsm_gideri'] + $_POST['adsl_gideri'] + $_POST['website_gideri']
		+ $_POST['muhasebeci_aidat'] + $_POST['kirtasiye_kartus_toner_fotokopi_gideri'] + $_POST['aylik_temizlik_gideri'] + $_POST['elektrik'] + $_POST['su']
		+ $aylik_top_icme_suyu_gideri + $aylik_ort_dogalgaz_gideri + $tum_otolar_aylik_top_gider + $tasarruflu_yakit_gideri + $_POST['aylik_muhtelif_gider'];
	$alistan_kdv_mahsubu = $alistan_kdv_matrahi - ($alistan_kdv_matrahi / 1.18);
	$kdv_on_kabulu = $satistan_olusan_kdv - $alistan_kdv_mahsubu;

	$top_kdv = $kdv_on_kabulu <= 0 ? 0 : $kdv_on_kabulu;

	$top_gider_vergi_kdv_haric = $mudur_maliyet + $sekreter_maliyet + $drk_hocalari_aylik_top_maas_maliyeti + $top_teorikci_maliyeti + $top_cayci_vs_gideri + $_POST['ikram_gideri']
		+ $aylik_top_yemek_gideri + $_POST['pist_kirasi'] + $_POST['bina_kirasi'] + $_POST['dinlenme_tesisi_kirasi'] + $pist_bina_kira_stopaji + $_POST['apt_sabit_gideri']
		+ $aylik_matbuu_gideri + $_POST['sabit_telefon_gideri'] + $_POST['gsm_gideri'] + $_POST['adsl_gideri'] + $_POST['website_gideri'] + $top_kitap_gideri + $_POST['muhasebeci_aidat']
		+ $_POST['otopark_gideri'] + $_POST['trf_ceza_aylik_ort'] + $e_sinav_aylik_gider + $_POST['kirtasiye_kartus_toner_fotokopi_gideri'] + $_POST['aylik_temizlik_gideri']
		+ $_POST['elektrik'] + $_POST['su'] + $aylik_top_icme_suyu_gideri + $aylik_ort_dogalgaz_gideri + $tum_otolar_aylik_top_gider + $tasarruflu_yakit_gideri + $_POST['aylik_muhtelif_gider']
		+ $_POST['belediye_vergisi_aylik_ort'] + $kurum_ruhsat_harci + $sozlesme_damga_vergisi;

	$kdvden_arindirilan_top_gelir = $top_ciro / 1.08;

	$vergiye_tabi_unsur = $kdvden_arindirilan_top_gelir - ($top_gider_vergi_kdv_haric - ($_POST['belediye_vergisi_aylik_ort'] + $kurum_ruhsat_harci + $sozlesme_damga_vergisi));

	$vergi_on_kabulu = $vergiye_tabi_unsur * 0.25;

	$top_vergi = $vergi_on_kabulu <= 0 ? 0 : $vergi_on_kabulu;
}

if (isset($_GET['logout'])) {
	session_destroy();
	Header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Maliyet Hesaplama Sonucu</title>
		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<div class="container">
			<h1>Maliyet Hesaplama Programı</h1>
			<form>
				<div style="width: 50%; float: left; padding: 0 2% 0 2%; display: flex; flex-direction: column;">
					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="indirim_zam_sonrasi_b_fiyat">İndirim ve Zam sonrası B ehliyet fiyatı</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="indirim_zam_sonrasi_b_fiyat" id="indirim_zam_sonrasi_b_fiyat" required value="<?php echo $indirim_zam_sonrasi_b_fiyat; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="mudur_maliyet">Müdür Maliyeti</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="mudur_maliyet" id="mudur_maliyet" required value="<?php echo $mudur_maliyet; ?>">
					
					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="sekreter_maliyet">Sekreter Maliyet</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="sekreter_maliyet" id="sekreter_maliyet" required value="<?php echo $sekreter_maliyet; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="drk_hocalari_aylik_top_maas_maliyeti">Direksiyon hocaları aylık toplam maaş maliyeti</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="drk_hocalari_aylik_top_maas_maliyeti" id="drk_hocalari_aylik_top_maas_maliyeti" required value="<?php echo $drk_hocalari_aylik_top_maas_maliyeti; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="gelinmeyen_dersler_icin_maas_tasarrufu">Gelinmeyen dersler için toplam direksiyon hocası maaşı tasarrufu</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="gelinmeyen_dersler_icin_maas_tasarrufu" id="gelinmeyen_dersler_icin_maas_tasarrufu" required value="<?php echo $gelinmeyen_dersler_icin_maas_tasarrufu; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="drk_hoca_tasarruflu_maliyeti">Direksiyon hocalarının tasarruf sonrası toplam maaş maliyeti</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="drk_hoca_tasarruflu_maliyeti" id="drk_hoca_tasarruflu_maliyeti" required value="<?php echo $drk_hoca_tasarruflu_maliyeti; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="top_teorikci_maliyeti">Teorik hoca maliyeti</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="top_teorikci_maliyeti" id="top_teorikci_maliyeti" required value="<?php echo $top_teorikci_maliyeti; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="top_cayci_vs_gideri">Toplam çaycı vs gideri</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="top_cayci_vs_gideri" id="top_cayci_vs_gideri" required value="<?php echo $top_cayci_vs_gideri; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="b_disi_aday_top_kar">B dışı adaylardan gelen kar katkısı</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="b_disi_aday_top_kar" id="b_disi_aday_top_kar" required value="<?php echo $b_disi_aday_top_kar; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="ikram_gideri">Toplam ikram gideri</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="ikram_gideri" id="ikram_gideri" required value="<?php echo $_POST['ikram_gideri']; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="aylik_top_yemek_gideri">Aylık toplam yemek gideri</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="aylik_top_yemek_gideri" id="aylik_top_yemek_gideri" required value="<?php echo $aylik_top_yemek_gideri; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="pist_kirasi">Pist kirası</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="pist_kirasi" id="pist_kirasi" required value="<?php echo $_POST['pist_kirasi']; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="bina_kirasi">Bina kirası</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="bina_kirasi" id="bina_kirasi" required value="<?php echo $_POST['bina_kirasi']; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="dinlenme_tesisi_kirasi">Sosyal tesis kirası</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="dinlenme_tesisi_kirasi" id="dinlenme_tesisi_kirasi" required value="<?php echo $_POST['dinlenme_tesisi_kirasi']; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="pist_bina_kira_stopaji">Kira Stopajı</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="pist_bina_kira_stopaji" id="pist_bina_kira_stopaji" required value="<?php echo $pist_bina_kira_stopaji; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="apt_sabit_gideri">Apartman Sabit Gideri</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="apt_sabit_gideri" id="apt_sabit_gideri" required value="<?php echo $_POST['apt_sabit_gideri']; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="aylik_matbuu_gideri">Aylık matbuu gideri</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="aylik_matbuu_gideri" id="aylik_matbuu_gideri" required value="<?php echo $aylik_matbuu_gideri; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="sabit_telefon_gideri">Sabit telefon gideri</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="sabit_telefon_gideri" id="sabit_telefon_gideri" required value="<?php echo $_POST['sabit_telefon_gideri']; ?>">
					
					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="gsm_gideri">GSM gideri</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="gsm_gideri" id="gsm_gideri" required value="<?php echo $_POST['gsm_gideri']; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="adsl_gideri">ADSL gideri</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="adsl_gideri" id="adsl_gideri" required value="<?php echo $_POST['adsl_gideri']; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="website_gideri">Website gideri</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="website_gideri" id="website_gideri" required value="<?php echo $_POST['website_gideri']; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="top_kitap_gideri">Aylık toplam kitap gideri</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="top_kitap_gideri" id="top_kitap_gideri" required value="<?php echo $top_kitap_gideri; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="muhasebeci_aidat">Muhasebeci Aidat</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="muhasebeci_aidat" id="muhasebeci_aidat" required value="<?php echo $_POST['muhasebeci_aidat']; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="otopark_gideri">Otopark gideri</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="otopark_gideri" id="otopark_gideri" required value="<?php echo $_POST['otopark_gideri']; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="trf_ceza_aylik_ort">Aylık ortalama trafik cezası bedeli</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="trf_ceza_aylik_ort" id="trf_ceza_aylik_ort" required value="<?php echo $_POST['trf_ceza_aylik_ort']; ?>">
					
					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="e_sinav_aylik_gider">Muhasebeci Aidat</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="e_sinav_aylik_gider" id="e_sinav_aylik_gider" required value="<?php echo $e_sinav_aylik_gider; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="kirtasiye_kartus_toner_fotokopi_gideri">Kırtasiye kartuş toner vs toplam gideri</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="kirtasiye_kartus_toner_fotokopi_gideri" id="kirtasiye_kartus_toner_fotokopi_gideri" required value="<?php echo $_POST['kirtasiye_kartus_toner_fotokopi_gideri']; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="aylik_temizlik_gideri">Aylık temizlik gideri</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="aylik_temizlik_gideri" id="aylik_temizlik_gideri" required value="<?php echo $_POST['aylik_temizlik_gideri']; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="elektrik">Elektrik gideri</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="elektrik" id="elektrik" required value="<?php echo $_POST['elektrik']; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="su">Su gideri</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="su" id="su" required value="<?php echo $_POST['su']; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="aylik_top_icme_suyu_gideri">Aylık toplam içme suyu gideri</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="aylik_top_icme_suyu_gideri" id="aylik_top_icme_suyu_gideri" required value="<?php echo $aylik_top_icme_suyu_gideri; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="aylik_ort_dogalgaz_gideri">Aylık ortalama doğalgaz gideri</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="aylik_ort_dogalgaz_gideri" id="aylik_ort_dogalgaz_gideri" required value="<?php echo $aylik_ort_dogalgaz_gideri; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="tum_otolar_aylik_top_gider">Araçların yakıt dışı gideri</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="tum_otolar_aylik_top_gider" id="tum_otolar_aylik_top_gider" required value="<?php echo $tum_otolar_aylik_top_gider; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="tasarruflu_yakit_gideri">Tasarruflu haliyle toplam yakıt gideri</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="tasarruflu_yakit_gideri" id="tasarruflu_yakit_gideri" required value="<?php echo $tasarruflu_yakit_gideri; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="aylik_muhtelif_gider">Aylık muhtelif gider</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="aylik_muhtelif_gider" id="aylik_muhtelif_gider" required value="<?php echo $_POST['aylik_muhtelif_gider']; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="belediye_vergisi_aylik_ort">Aylık ortalama belediye vergisi</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="belediye_vergisi_aylik_ort" id="belediye_vergisi_aylik_ort" required value="<?php echo $_POST['belediye_vergisi_aylik_ort']; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="kurum_ruhsat_harci">Kurum harcı</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="kurum_ruhsat_harci" id="kurum_ruhsat_harci" required value="<?php echo $kurum_ruhsat_harci; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="sozlesme_damga_vergisi">Sözleşme damga vergisi</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="sozlesme_damga_vergisi" id="sozlesme_damga_vergisi" required value="<?php echo $sozlesme_damga_vergisi; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="top_ciro">Toplam Ciro</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="top_ciro" id="top_ciro" required value="<?php echo $top_ciro; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="satistan_kdv_matrahi">Satıştan KDV matrahı</label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="satistan_kdv_matrahi" id="satistan_kdv_matrahi" required value="<?php echo $_POST['satistan_kdv_matrahi']; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="su"></label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="su" id="su" required value="<?php echo $_POST['su']; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="asgari_ucretli_tazminat_maliyeti"></label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="asgari_ucretli_tazminat_maliyeti" id="asgari_ucretli_tazminat_maliyeti" required value="<?php echo $asgari_ucretli_tazminat_maliyeti; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="asgari_ucretli_tazminat_maliyeti"></label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="asgari_ucretli_tazminat_maliyeti" id="asgari_ucretli_tazminat_maliyeti" required value="<?php echo $asgari_ucretli_tazminat_maliyeti; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="asgari_ucretli_tazminat_maliyeti"></label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="asgari_ucretli_tazminat_maliyeti" id="asgari_ucretli_tazminat_maliyeti" required value="<?php echo $asgari_ucretli_tazminat_maliyeti; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="asgari_ucretli_tazminat_maliyeti"></label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="asgari_ucretli_tazminat_maliyeti" id="asgari_ucretli_tazminat_maliyeti" required value="<?php echo $asgari_ucretli_tazminat_maliyeti; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="asgari_ucretli_tazminat_maliyeti"></label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="asgari_ucretli_tazminat_maliyeti" id="asgari_ucretli_tazminat_maliyeti" required value="<?php echo $asgari_ucretli_tazminat_maliyeti; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="asgari_ucretli_tazminat_maliyeti"></label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="asgari_ucretli_tazminat_maliyeti" id="asgari_ucretli_tazminat_maliyeti" required value="<?php echo $asgari_ucretli_tazminat_maliyeti; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="asgari_ucretli_tazminat_maliyeti"></label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="asgari_ucretli_tazminat_maliyeti" id="asgari_ucretli_tazminat_maliyeti" required value="<?php echo $asgari_ucretli_tazminat_maliyeti; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="asgari_ucretli_tazminat_maliyeti"></label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="asgari_ucretli_tazminat_maliyeti" id="asgari_ucretli_tazminat_maliyeti" required value="<?php echo $asgari_ucretli_tazminat_maliyeti; ?>">

					<label style="width: 95%; float: left; padding-left: 2%; padding-right: 2%;" for="asgari_ucretli_tazminat_maliyeti"></label>
					<input disabled style="width: 95%; float: left;" type="number" step="0.1" name="asgari_ucretli_tazminat_maliyeti" id="asgari_ucretli_tazminat_maliyeti" required value="<?php echo $asgari_ucretli_tazminat_maliyeti; ?>">
				</div>

				<input disabled class="submit-btn" type="submit" name="calculate" id="calculate" value="Hesapla">
				<input disabled class="logout-btn" onclick="window.location.replace('user.php?logout=true')" name="logout" value="Çıkış Yap">
			</form>
		</div>
	</body>
</html>





