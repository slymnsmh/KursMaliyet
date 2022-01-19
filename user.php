<?php

session_start();

if (empty($_SESSION['user_id'])) {
	header("location: index.php?from=userPage");
	exit();
}

$info = "";

if (isset($_GET['logout'])) {
	session_destroy();
	Header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Maliyet Hesaplama</title>
		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<div class="container">
			<h1>Maliyet Hesaplama Programı</h1>
			<form method="post" action="user.php">
				<div style="width: 50%; float: left; padding: 0 2% 0 2%;">
					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="kac_b">B Aday Sayısı</label>
					<input style="width: 60%; float: left;" type="number" step="0.1" name="kac_b" id="kac_b" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="valilik_b_taban">Valilik B Taban Fiyat</label>
					<input style="width: 60%; float: left;" type="number" step="0.1" name="valilik_b_taban" id="valilik_b_taban" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="tabana_ekle">Yaptığınız zam</label>
					<input style="width: 60%; float: left;" type="number" step="0.1" name="tabana_ekle" id="tabana_ekle" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="indirim_tutari">İndirim tutarı</label>
					<input style="width: 60%; float: left;" type="number" step="0.1" name="indirim_tutari" id="indirim_tutari" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="ozel_ders_ucret">Özel Direksiyon Ders Ücreti</label>
					<input style="width: 60%; float: left;" type="number" step="0.1" name="ozel_ders_ucret" id="ozel_ders_ucret" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="bsrz_ozel_ders_ucret">Başarısız Aday Özel Ders Ücreti</label>
					<input style="width: 60%; float: left;" type="number" step="0.1" name="bsrz_ozel_ders_ucret" id="bsrz_ozel_ders_ucret" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="tazminata_esas_brut">Bir kişinin tazminata esas brütü</label>
					<input style="width: 60%; float: left;" type="number" step="0.1" name="tazminata_esas_brut" id="tazminata_esas_brut" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="asgari_ucretli_personel_brutu_sgk_haric">Asgari Ücretli SGK Hariç Brüt</label>
					<input style="width: 60%; float: left;" type="number" step="0.1" name="asgari_ucretli_personel_brutu_sgk_haric" id="asgari_ucretli_personel_brutu_sgk_haric" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="asgari_ucretli_personel_sgk_maliyeti">Asgari Ücretli SGK Maliyeti</label>
					<input style="width: 60%; float: left;" type="number" step="0.1" name="asgari_ucretli_personel_sgk_maliyeti" id="asgari_ucretli_personel_sgk_maliyeti" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="minimum_arac_sayisi">Minimum otomobil sayısı</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="minimum_arac_sayisi" id="minimum_arac_sayisi" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="bir_oto_kac_b">1 otomobil kaç B adayı alabilir</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="bir_oto_kac_b" id="bir_oto_kac_b" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="ihtiyac_disi_otomobil_sayisi">Kenarda bekletilen araba sayısı</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="ihtiyac_disi_otomobil_sayisi" id="ihtiyac_disi_otomobil_sayisi" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="sinif_kontenjani">Sınıf kontenjanı</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="sinif_kontenjani" id="sinif_kontenjani" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="her_b_kac_saat_drk">B adayı direksiyon çalışma saati</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="her_b_kac_saat_drk" id="her_b_kac_saat_drk" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="kisi_basi_yemek_gideri">Kişi başı yemek gideri</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="kisi_basi_yemek_gideri" id="kisi_basi_yemek_gideri" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="ikram_gideri">İkram gideri (Aylık)</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="ikram_gideri" id="ikram_gideri" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="teorik_dersler_kac_saat">Teorik dersler kaç saat</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="teorik_dersler_kac_saat" id="teorik_dersler_kac_saat" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="saat_ucretli_min_brut">Saat ücretli hocaya verilecek minimum brüt</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="saat_ucretli_min_brut" id="saat_ucretli_min_brut" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="trafikci_toplam_ucret">Trafik hocası net ücret</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="trafikci_toplam_ucret" id="trafikci_toplam_ucret" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="ilkyardimci_toplam_ucret">İlkyardım hocası net ücret</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="ilkyardimci_toplam_ucret" id="ilkyardimci_toplam_ucret" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="cayci_vs_sayisi">Çaycı, temizlikçi vs. sayısı</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="cayci_vs_sayisi" id="cayci_vs_sayisi" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="mudur_farki">Müdürün diğer asgari ücretlilerden farkı</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="mudur_farki" id="mudur_farki" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="sekreter_farki">Sekreterin diğer asgari ücretlilerden farkı</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="sekreter_farki" id="sekreter_farki" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="pist_kirasi">Pist kirası</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="pist_kirasi" id="pist_kirasi" required>
				</div>
				
				<div style="width: 50%; float: right; padding: 0 2% 0 2%;">
					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="bina_kirasi">Bina kirası</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="bina_kirasi" id="bina_kirasi" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="dinlenme_tesisi_kirasi">Dinlenme tesisi kirası (varsa)</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="dinlenme_tesisi_kirasi" id="dinlenme_tesisi_kirasi" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="apt_sabit_gideri">Apartman sabit gideri</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="apt_sabit_gideri" id="apt_sabit_gideri" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="cemiyet_evrak_bedel">Cemiyetten aylık olarak alınan evrak bedeli</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="cemiyet_evrak_bedel" id="cemiyet_evrak_bedel" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="sabit_telefon_gideri">Sabit telefon gideri</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="sabit_telefon_gideri" id="sabit_telefon_gideri" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="gsm_gideri">GSM gideri</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="gsm_gideri" id="gsm_gideri" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="adsl_gideri">ADSL gideri</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="adsl_gideri" id="adsl_gideri" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="website_gideri">Website gideri</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="website_gideri" id="website_gideri" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="kitap_birim_fiyat">Adaya verilen kitabın birim fiyatı</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="kitap_birim_fiyat" id="kitap_birim_fiyat" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="muhasebeci_aidat">Muhasebeci aidatı</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="muhasebeci_aidat" id="muhasebeci_aidat" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="otopark_gideri">Otopark gideri</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="otopark_gideri" id="otopark_gideri" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="trk_ceza_aylik_ort">Aylık ortalama trafik cezası bedeli</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="trk_ceza_aylik_ort" id="trk_ceza_aylik_ort" required>
					
					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="e_sinav_yillik_aidat">E Sınav sistem için yıllık aidat</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="e_sinav_yillik_aidat" id="e_sinav_yillik_aidat" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="kirtasiye_kartus_toner_fotokopi_gideri">Kırtasiye Kartuş Fotokopi Gideri</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="kirtasiye_kartus_toner_fotokopi_gideri" id="kirtasiye_kartus_toner_fotokopi_gideri" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="aylik_temizlik_gideri">Aylık temizlik gideri</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="aylik_temizlik_gideri" id="aylik_temizlik_gideri" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="elektrik">Elektrik gideri</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="elektrik" id="elektrik" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="su">Su gideri</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="su" id="su" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="damacana_su_fiyati">Damacana Su Birim Fiyatı</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="damacana_su_fiyati" id="damacana_su_fiyati" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="muhasebeci_aidat">Ayda kaç damacana su içiliyor</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="muhasebeci_aidat" id="muhasebeci_aidat" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="yaz_aylari_dogalgaz">Yaz ayları doğalgaz gideri</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="yaz_aylari_dogalgaz" id="yaz_aylari_dogalgaz" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="kis_dogalgaz_gideri">Kış ayları doğalgaz gideri</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="kis_dogalgaz_gideri" id="kis_dogalgaz_gideri" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="belediye_vergisi_aylik_ort">Belediye vergileri aylık ortalama</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="belediye_vergisi_aylik_ort" id="belediye_vergisi_aylik_ort" required>

					<label style="width: 40%; float: left; padding-left: 2%; padding-right: 2%;" for="aylik_muhtelif_gider">Aylık muhtelif giderler</label>
					<input style="width: 60%; float: left;" type="number" step="1" name="aylik_muhtelif_gider" id="aylik_muhtelif_gider" required>
				</div>

				<input class="submit-btn" type="submit" name="save" id="save" value="Kaydet">
				<input class="logout-btn" onclick="window.location.replace('user.php?logout=true')" name="logout" value="Çıkış Yap">
			</form>
		</div>
	</body>
</html>





