import os
import docx
from docx import Document
from docx.shared import Inches, Pt, RGBColor, Cm
from docx.enum.text import WD_ALIGN_PARAGRAPH, WD_LINE_SPACING
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml import OxmlElement, parse_xml
from docx.oxml.ns import nsdecls, qn

def set_cell_margins(cell, top=100, bottom=100, left=150, right=150):
    tcPr = cell._tc.get_or_add_tcPr()
    tcMar = OxmlElement('w:tcMar')
    for m, val in [('top', top), ('bottom', bottom), ('left', left), ('right', right)]:
        node = OxmlElement(f'w:{m}')
        node.set(qn('w:w'), str(val))
        node.set(qn('w:type'), 'dxa')
        tcMar.append(node)
    tcPr.append(tcMar)

def set_cell_shading(cell, color_hex):
    shading_xml = f'<w:shd {nsdecls("w")} w:fill="{color_hex}"/>'
    cell._tc.get_or_add_tcPr().append(parse_xml(shading_xml))

def set_cell_border(cell, **kwargs):
    tcPr = cell._tc.get_or_add_tcPr()
    tcBorders = OxmlElement('w:tcBorders')
    for edge in ('top', 'left', 'bottom', 'right', 'insideH', 'insideV'):
        edge_data = kwargs.get(edge)
        if edge_data:
            tag = f'w:{edge}'
            element = OxmlElement(tag)
            element.set(qn('w:val'), edge_data.get('val', 'single'))
            element.set(qn('w:sz'), str(edge_data.get('sz', 4)))
            element.set(qn('w:space'), '0')
            element.set(qn('w:color'), edge_data.get('color', 'CCCCCC'))
            tcBorders.append(element)
    tcPr.append(tcBorders)

def add_page_number_field(run):
    fldSimple = OxmlElement('w:fldSimple')
    fldSimple.set(qn('w:instr'), 'PAGE')
    run._r.append(fldSimple)

def create_proposal_document():
    doc = Document()
    
    # ----------------------------------------------------
    # Page Setup (Standard Indonesian Academic: A4)
    # Margin: Top: 3 cm, Left: 4 cm, Bottom: 3 cm, Right: 3 cm
    # ----------------------------------------------------
    for section in doc.sections:
        section.page_width = Cm(21.0)
        section.page_height = Cm(29.7)
        section.top_margin = Cm(3.0)
        section.bottom_margin = Cm(3.0)
        section.left_margin = Cm(4.0)
        section.right_margin = Cm(3.0)
        section.different_first_page_header_footer = True
        
    # Styles config
    normal_style = doc.styles['Normal']
    normal_style.font.name = 'Times New Roman'
    normal_style.font.size = Pt(12)
    normal_style.font.color.rgb = RGBColor(0x1F, 0x24, 0x2E)
    normal_style.paragraph_format.line_spacing = 1.5
    normal_style.paragraph_format.space_after = Pt(4)
    normal_style.paragraph_format.space_before = Pt(0)

    # ====================================================
    # SECTION 1: COVER PAGE
    # ====================================================
    p_header = doc.add_paragraph()
    p_header.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_header.paragraph_format.line_spacing = 1.15
    p_header.paragraph_format.space_after = Pt(2)
    r1 = p_header.add_run("COMPUTING PROJECT\nPROPOSAL")
    r1.bold = True
    r1.font.size = Pt(14)
    r1.font.name = 'Times New Roman'

    p_title = doc.add_paragraph()
    p_title.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_title.paragraph_format.space_before = Pt(12)
    p_title.paragraph_format.space_after = Pt(16)
    p_title.paragraph_format.line_spacing = 1.15
    r_title = p_title.add_run("TRINET-BILL: TRICORE NETWORK BILLING — PENGEMBANGAN SISTEM INFORMASI MANAJEMEN OPERASIONAL DAN PENAGIHAN INTERNET SERVICE PROVIDER BERBASIS WEB PADA TRICORE DATA MEDIA PURWOKERTO")
    r_title.bold = True
    r_title.font.size = Pt(12.5)
    r_title.font.name = 'Times New Roman'

    # Logo
    p_logo = doc.add_paragraph()
    p_logo.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_logo.paragraph_format.space_before = Pt(4)
    p_logo.paragraph_format.space_after = Pt(12)
    if os.path.exists("telkom_logo.png"):
        p_logo.add_run().add_picture("telkom_logo.png", width=Inches(2.1))
    else:
        r_logo = p_logo.add_run("[ LOGO UNIVERSITAS TELKOM ]")
        r_logo.bold = True

    # Project Managers
    p_pm = doc.add_paragraph()
    p_pm.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_pm.paragraph_format.line_spacing = 1.15
    p_pm.paragraph_format.space_before = Pt(4)
    p_pm.paragraph_format.space_after = Pt(2)
    r_pm_h = p_pm.add_run("Project Manager:\n")
    r_pm_h.bold = True
    r_pm_h.font.size = Pt(11)
    
    # PM Table (Centered & Clean)
    pm_table = doc.add_table(rows=2, cols=2)
    pm_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    pms = [
        ("Valisha Atthalia Naura Irfan", "2311102160"),
        ("Amelia Azmi", "2311102152")
    ]
    for i, (name, nim) in enumerate(pms):
        row = pm_table.rows[i]
        c1, c2 = row.cells[0], row.cells[1]
        c1.width = Inches(3.2)
        c2.width = Inches(1.8)
        p1 = c1.paragraphs[0]
        p1.text = name
        p1.runs[0].font.size = Pt(11)
        p1.alignment = WD_ALIGN_PARAGRAPH.LEFT
        p2 = c2.paragraphs[0]
        p2.text = nim
        p2.runs[0].font.size = Pt(11)
        p2.alignment = WD_ALIGN_PARAGRAPH.RIGHT
        set_cell_margins(c1, top=15, bottom=15, left=0, right=0)
        set_cell_margins(c2, top=15, bottom=15, left=0, right=0)

    # Team Members
    p_tm = doc.add_paragraph()
    p_tm.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_tm.paragraph_format.line_spacing = 1.15
    p_tm.paragraph_format.space_before = Pt(6)
    p_tm.paragraph_format.space_after = Pt(2)
    r_tm_h = p_tm.add_run("Team Members:")
    r_tm_h.bold = True
    r_tm_h.font.size = Pt(11)

    members = [
        ("Kanasya Abdi Aziz", "2311102140"),
        ("Agnes Refilina Fiska", "2311102126"),
        ("Arsya Fathiha Rahman", "2311102152"),
        ("Valisha Atthalia Naura Irfan", "2311102160"),
        ("Afrizal Dwi Nugraha", "2311102136"),
        ("Amelia Azmi", "2311102152")
    ]
    
    tm_table = doc.add_table(rows=len(members), cols=2)
    tm_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    for i, (name, nim) in enumerate(members):
        row = tm_table.rows[i]
        c1, c2 = row.cells[0], row.cells[1]
        c1.width = Inches(3.2)
        c2.width = Inches(1.8)
        p1 = c1.paragraphs[0]
        p1.text = name
        p1.runs[0].font.size = Pt(11)
        p1.alignment = WD_ALIGN_PARAGRAPH.LEFT
        p2 = c2.paragraphs[0]
        p2.text = nim
        p2.runs[0].font.size = Pt(11)
        p2.alignment = WD_ALIGN_PARAGRAPH.RIGHT
        set_cell_margins(c1, top=12, bottom=12, left=0, right=0)
        set_cell_margins(c2, top=12, bottom=12, left=0, right=0)

    # Supervisor
    p_sup = doc.add_paragraph()
    p_sup.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_sup.paragraph_format.line_spacing = 1.15
    p_sup.paragraph_format.space_before = Pt(6)
    p_sup.paragraph_format.space_after = Pt(2)
    r_sup_h = p_sup.add_run("Supervisor:\n")
    r_sup_h.bold = True
    r_sup_h.font.size = Pt(11)
    r_sup_n = p_sup.add_run("Nama Lengkap Dosen Pembimbing, S.T., M.T.")
    r_sup_n.font.size = Pt(11)

    # Institution
    p_inst = doc.add_paragraph()
    p_inst.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_inst.paragraph_format.line_spacing = 1.15
    p_inst.paragraph_format.space_before = Pt(12)
    p_inst.paragraph_format.space_after = Pt(0)
    r_inst = p_inst.add_run("PROGRAM STUDI S-1 TEKNIK INFORMATIKA\nDIREKTORAT KAMPUS PURWOKERTO – UNIVERSITAS TELKOM\nMARET 2026")
    r_inst.bold = True
    r_inst.font.size = Pt(12)

    # ====================================================
    # SECTION 2: FRONT MATTER (Roman page numbering: i, ii)
    # ====================================================
    front_section = doc.add_section(docx.enum.section.WD_SECTION.NEW_PAGE)
    front_section.top_margin = Cm(3.0)
    front_section.bottom_margin = Cm(3.0)
    front_section.left_margin = Cm(4.0)
    front_section.right_margin = Cm(3.0)
    front_section.different_first_page_header_footer = False
    
    # Configure Roman Page Numbering
    sectPr = front_section._sectPr
    pgNumType = OxmlElement('w:pgNumType')
    pgNumType.set(qn('w:fmt'), 'romanLower')
    pgNumType.set(qn('w:start'), '1')
    sectPr.append(pgNumType)

    # Footer for front section
    footer_front = front_section.footer
    p_foot_f = footer_front.paragraphs[0]
    p_foot_f.alignment = WD_ALIGN_PARAGRAPH.RIGHT
    r_foot_f = p_foot_f.add_run()
    r_foot_f.font.name = 'Times New Roman'
    r_foot_f.font.size = Pt(10)
    add_page_number_field(r_foot_f)

    # --- Executive Summary ---
    p_ex_h = doc.add_paragraph()
    p_ex_h.paragraph_format.space_before = Pt(0)
    p_ex_h.paragraph_format.space_after = Pt(12)
    r_ex_h = p_ex_h.add_run("Executive Summary")
    r_ex_h.bold = True
    r_ex_h.font.size = Pt(14)
    r_ex_h.font.color.rgb = RGBColor(0x1E, 0x3A, 0x8A)

    p_ex_1 = doc.add_paragraph()
    p_ex_1.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
    p_ex_1.paragraph_format.line_spacing = 1.5
    p_ex_1.paragraph_format.space_after = Pt(8)
    p_ex_1.add_run(
        "Tingginya ketergantungan masyarakat pada akses internet pita lebar (broadband) berbasis serat optik mendorong penyedia layanan internet lokal untuk meningkatkan keandalan sistem tata kelola pelanggan dan penagihannya. TRICORE DATA MEDIA, sebagai penyedia jasa internet yang melayani wilayah Purwokerto Timur, Purwokerto Wetan, dan Sokaraja, saat ini menghadapi kendala operasional yang nyata. Proses pengelolaan data pelanggan, pencatatan titik Optical Distribution Point (ODP), pemetaan alamat IP, hingga pencocokan bukti transfer pembayaran bulanan masih dilakukan secara manual menggunakan lembar sebar dan pesan instan personal. Kondisi ini rentan memicu keterlambatan penerbitan faktur tagihan, kekeliruan penetapan status isolir, dan keterbatasan transparansi bagi pelanggan."
    )

    p_ex_2 = doc.add_paragraph()
    p_ex_2.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
    p_ex_2.paragraph_format.line_spacing = 1.5
    p_ex_2.paragraph_format.space_after = Pt(8)
    p_ex_2.add_run(
        "Sebagai solusi terintegrasi, proyek ini menghadirkan TRINET-BILL (TRIcore Network Billing), sebuah sistem informasi manajemen operasional dan penagihan ISP berbasis web. TRINET-BILL dirancang untuk mengintegrasikan proses bisnis inti ke dalam satu platform tunggal, yang meliputi portal publik interaktif (pemeriksaan cakupan area fiber optic, simulasi paket internet 15–50 Mbps, formulir registrasi online, dan form cek tagihan mandiri), mesin penerbitan tagihan berkala otomatis (automated billing engine), integrasi gateway notifikasi WhatsApp bisnis, serta portal admin/mitra terproteksi untuk memonitor siklus hidup pelanggan dan laporan keuangan secara real-time."
    )

    p_ex_3 = doc.add_paragraph()
    p_ex_3.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
    p_ex_3.paragraph_format.line_spacing = 1.5
    p_ex_3.paragraph_format.space_after = Pt(14)
    p_ex_3.add_run(
        "Pengembangan TRINET-BILL dilaksanakan menggunakan metodologi Agile Software Development (Scrum) dengan kerangka teknologi Laravel 12 (PHP 8.5), Tailwind CSS v4, Vite 8, basis data relasional, dan kontainerisasi Docker. Luaran proyek mencakup dokumen formal rekayasa perangkat lunak (SRS, SDD, UAT, User Guide), aplikasi web TRINET-BILL siap produksi, serta laporan akhir Computing Project. Implementasi TRINET-BILL ditargetkan mampu memangkas waktu rekonsiliasi tagihan hingga 80%, mengeliminasi kesalahan alokasi data teknis ODP, dan meningkatkan kepuasan pelanggan melalui transparansi layanan."
    )

    # --- Table of Content Page ---
    doc.add_page_break()

    p_toc_h = doc.add_paragraph()
    p_toc_h.paragraph_format.space_before = Pt(0)
    p_toc_h.paragraph_format.space_after = Pt(12)
    r_toc_h = p_toc_h.add_run("Table of Content")
    r_toc_h.bold = True
    r_toc_h.font.size = Pt(14)
    r_toc_h.font.color.rgb = RGBColor(0x1E, 0x3A, 0x8A)

    toc_items = [
        ("Executive Summary", "i", False),
        ("Table of Content", "ii", False),
        ("1. Background (Latar Belakang)", "1", True),
        ("2. Problem Statement (Rumusan Masalah)", "2", True),
        ("3. Objectives (Tujuan Proyek)", "2", True),
        ("4. Scope and Limitations (Ruang Lingkup dan Batasan)", "3", True),
        ("5. Business Requirement Specification (BRS)", "3", True),
        ("    5.1. Business Goals", "3", False),
        ("    5.2. Stakeholders", "4", False),
        ("    5.3. Business Process Description (AS-IS dan TO-BE)", "4", False),
        ("    5.4. High-Level Business Requirements", "5", False),
        ("    5.5. Business Rules", "5", False),
        ("6. Methodology (Metodologi Pengembangan)", "6", True),
        ("    6.1. Metode Pengembangan Perangkat Lunak", "6", False),
        ("    6.2. Perangkat dan Lingkungan Pengembangan", "6", False),
        ("    6.3. Tahapan Pengerjaan Proyek", "6", False),
        ("7. Proposed System Overview (Solusi TRINET-BILL)", "7", True),
        ("8. Project Deliverables", "8", True),
        ("9. Project Timeline", "8", True),
        ("10. Team Members and Roles", "9", True),
        ("11. References (Daftar Pustaka)", "10", True),
    ]

    toc_table = doc.add_table(rows=len(toc_items), cols=2)
    toc_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    for idx, (title, page_num, is_bold) in enumerate(toc_items):
        row = toc_table.rows[idx]
        c1, c2 = row.cells[0], row.cells[1]
        c1.width = Inches(5.3)
        c2.width = Inches(0.9)
        
        p1 = c1.paragraphs[0]
        p1.paragraph_format.line_spacing = 1.15
        p1.paragraph_format.space_after = Pt(2)
        r1 = p1.add_run(title)
        r1.font.name = 'Times New Roman'
        r1.font.size = Pt(11)
        r1.bold = is_bold
        
        p2 = c2.paragraphs[0]
        p2.paragraph_format.line_spacing = 1.15
        p2.paragraph_format.space_after = Pt(2)
        p2.alignment = WD_ALIGN_PARAGRAPH.RIGHT
        r2 = p2.add_run(page_num)
        r2.font.name = 'Times New Roman'
        r2.font.size = Pt(11)
        r2.bold = is_bold
        
        set_cell_margins(c1, top=20, bottom=20, left=0, right=0)
        set_cell_margins(c2, top=20, bottom=20, left=0, right=0)

    # ====================================================
    # SECTION 3: BODY (Arabic page numbering: 1, 2, ...)
    # ====================================================
    body_section = doc.add_section(docx.enum.section.WD_SECTION.NEW_PAGE)
    body_section.top_margin = Cm(3.0)
    body_section.bottom_margin = Cm(3.0)
    body_section.left_margin = Cm(4.0)
    body_section.right_margin = Cm(3.0)
    body_section.different_first_page_header_footer = False

    # Arabic page numbers starting at 1
    sectPr_b = body_section._sectPr
    pgNumType_b = OxmlElement('w:pgNumType')
    pgNumType_b.set(qn('w:fmt'), 'decimal')
    pgNumType_b.set(qn('w:start'), '1')
    sectPr_b.append(pgNumType_b)

    # Footer for body
    footer_body = body_section.footer
    p_foot_b = footer_body.paragraphs[0]
    p_foot_b.alignment = WD_ALIGN_PARAGRAPH.RIGHT
    r_foot_b = p_foot_b.add_run()
    r_foot_b.font.name = 'Times New Roman'
    r_foot_b.font.size = Pt(10)
    add_page_number_field(r_foot_b)

    def add_section_title(title_text):
        p = doc.add_paragraph()
        p.paragraph_format.space_before = Pt(14)
        p.paragraph_format.space_after = Pt(6)
        p.paragraph_format.keep_with_next = True
        run = p.add_run(title_text)
        run.bold = True
        run.font.size = Pt(13)
        run.font.name = 'Times New Roman'
        run.font.color.rgb = RGBColor(0x1E, 0x3A, 0x8A)
        return p

    def add_subsection_title(sub_text):
        p = doc.add_paragraph()
        p.paragraph_format.space_before = Pt(10)
        p.paragraph_format.space_after = Pt(4)
        p.paragraph_format.keep_with_next = True
        run = p.add_run(sub_text)
        run.bold = True
        run.font.size = Pt(12)
        run.font.name = 'Times New Roman'
        run.font.color.rgb = RGBColor(0x11, 0x18, 0x27)
        return p

    def add_body_paragraph(text, space_after=6):
        p = doc.add_paragraph()
        p.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
        p.paragraph_format.line_spacing = 1.5
        p.paragraph_format.space_after = Pt(space_after)
        run = p.add_run(text)
        run.font.name = 'Times New Roman'
        run.font.size = Pt(12)
        return p

    def add_bullet(bold_prefix, text):
        p = doc.add_paragraph(style='List Bullet')
        p.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
        p.paragraph_format.line_spacing = 1.5
        p.paragraph_format.space_after = Pt(4)
        if bold_prefix:
            r_b = p.add_run(bold_prefix + ": ")
            r_b.bold = True
            r_b.font.name = 'Times New Roman'
            r_b.font.size = Pt(12)
        r_t = p.add_run(text)
        r_t.font.name = 'Times New Roman'
        r_t.font.size = Pt(12)
        return p

    # --- 1. Background ---
    add_section_title("1. Background (Latar Belakang)")
    add_body_paragraph(
        "Akses internet pita lebar (broadband) berkecepatan tinggi telah menjadi kebutuhan esensial dalam mendukung aktivitas operasional usaha, pendidikan, dan kebutuhan rumah tangga modern. Di era digital saat ini, penyedia layanan internet (Internet Service Provider / ISP) lokal memiliki peran strategis dalam menghadirkan jaringan kabel serat optik (Fiber to the Home / FTTH) hingga ke lingkungan perumahan. Salah satu penyedia jasa internet yang melayani masyarakat di kawasan Purwokerto dan sekitarnya adalah TRICORE DATA MEDIA, dengan wilayah jangkauan aktif di Purwokerto Timur, Purwokerto Wetan, dan Sokaraja."
    )
    add_body_paragraph(
        "Seiring bertambahnya jumlah pelanggan dan titik distribusi optik, operasional TRICORE DATA MEDIA masih dijalankan secara konvensional dengan mencatat data pelanggan pada lembar sebar (spreadsheet) terpisah dan mengandalkan komunikasi obrolan instan yang tidak terstruktur. Pola kerja tersebut memunculkan sejumlah tantangan nyata:"
    )
    add_bullet("Kerentanan Selisih Rekonsiliasi Finansial", "Pencatatan mutasi pembayaran ratusan pelanggan yang dilakukan secara manual membutuhkan waktu verifikasi yang panjang dan rentan memicu selisih pencatatan tagihan.")
    add_bullet("Fragmentasi Data Teknis Lapangan", "Dokumentasi kapasitas port Optical Distribution Point (ODP) dan alokasi alamat IP pelanggan belum terintegrasi ke dalam basis data terpusat, menyulitkan teknisi lapangan dalam proses pemasangan baru maupun perbaikan kendala.")
    add_bullet("Keterbatasan Layanan Mandiri bagi Pelanggan", "Calon pelanggan belum memiliki sarana digital untuk memverifikasi ketersediaan jaringan di lokasi mereka, sementara pelanggan aktif masih harus menanyakan rincian tagihan secara manual kepada staf administrasi.")
    add_body_paragraph(
        "Menjawab tantangan tersebut, diperlukan solusi perangkat lunak terintegrasi yang diberi nama TRINET-BILL (TRIcore Network Billing). TRINET-BILL dirancang sebagai platform web all-in-one yang menghubungkan portal promosi publik, manajemen data master pelanggan, pemetaan teknis ODP/IP, dan otomasi penagihan bulanan secara efektif dan akuntabel."
    )

    # --- 2. Problem Statement ---
    add_section_title("2. Problem Statement (Rumusan Masalah)")
    add_body_paragraph(
        "Pengelolaan operasional, pendokumentasian titik distribusi optik, dan penagihan tagihan bulanan pada TRICORE DATA MEDIA saat ini masih berjalan secara manual dan terfragmentasi sehingga menurunkan efisiensi layanan serta memperbesar risiko kesalahan pembukuan transaksi. Ketiadaan sistem informasi terintegrasi mengakibatkan staf administrasi memerlukan waktu rekonsiliasi yang lama setiap tanggal jatuh tempo, status isolir pelanggan menunggak tidak terkelola secara otomatis, dan data teknis jaringan rentan hilang saat terjadi pergeseran staf lapangan."
    )

    # --- 3. Objectives ---
    add_section_title("3. Objectives (Tujuan Proyek)")
    add_body_paragraph(
        "Tujuan pengembangan sistem TRINET-BILL (TRIcore Network Billing) dirumuskan berdasarkan kriteria SMART (Specific, Measurable, Achievable, Relevant, Time-bound):"
    )
    add_bullet("Tujuan Utama", "Membangun dan mengimplementasikan sistem informasi manajemen operasional dan penagihan internet berbasis web, TRINET-BILL (TRIcore Network Billing), untuk TRICORE DATA MEDIA dalam jangka waktu 16 minggu pengerjaan.")
    add_bullet("Otomasi Penagihan (Automated Billing)", "Memangkas waktu penerbitan faktur tagihan dan verifikasi pembayaran bulanan pelanggan hingga 80% melalui fitur penerbitan invoice massal otomatis pada TRINET-BILL.")
    add_bullet("Sentralisasi Data Pelanggan & ODP", "Menyediakan repositori basis data terpusat dengan tingkat akurasi 100% untuk data identitas pelanggan, paket internet (15–50 Mbps), posisi titik ODP, dan pemetaan alamat IP.")
    add_bullet("Portal Layanan Mandiri (Self-Service Portal)", "Menyediakan antarmuka publik interaktif pada website TRINET-BILL yang memungkinkan calon pelanggan mengecek cakupan area fiber optic dan memudahkan pelanggan aktif memeriksa rincian tagihan secara mandiri.")
    add_bullet("Integrasi Notifikasi Transaksi", "Mengintegrasikan sistem TRINET-BILL dengan format pesan WhatsApp untuk mempercepat konfirmasi bukti bayar dan pengiriman kuitansi digital dalam durasi kurang dari 5 menit.")

    # --- 4. Scope and Limitations ---
    add_section_title("4. Scope and Limitations (Ruang Lingkup dan Batasan)")
    add_subsection_title("4.1. Ruang Lingkup Sistem (In-Scope)")
    add_bullet("Portal Publik TRINET-BILL", "Katalog paket internet (Hemat 15 Mbps, Family 20 Mbps, Favorit 25 Mbps, Turbo 35 Mbps, Ultimate 50 Mbps), peta interaktif cakupan wilayah (Coverage Area), formulir pendaftaran pelanggan baru online, dan fitur cek tagihan mandiri.")
    add_bullet("Portal Administrasi & Mitra TRINET-BILL", "Autentikasi berjenjang (Super Admin dan Mitra/Teknisi), dasbor visual indikator kinerja (omzet, total pelanggan aktif, rasio invoice pending), dan manajemen master paket internet.")
    add_bullet("Modul Manajemen Teknis & Pelanggan", "Pengelolaan data pelanggan (CRUD lengkap), status layanan (Aktif, Pending, Terisolir), serta pencatatan port ODP dan alokasi IP address.")
    add_bullet("Modul Penagihan & Invoice Digital", "Penerbitan tagihan berkala otomatis, konfirmasi pelunasan, pencetakan bukti bayar/invoice dalam format PDF, dan penyusunan template pesan WhatsApp.")

    add_subsection_title("4.2. Batasan Sistem (Out-of-Scope)")
    add_bullet("Otomasi Konfigurasi Hardware Router/OLT", "Sistem TRINET-BILL pada rilis ini fokus pada lapisan tata kelola data bisnis dan belum mengeksekusi skrip konfigurasi langsung ke router MikroTik/ZTE via API/SNMP.")
    add_bullet("Integrasi Payment Gateway Perbankan", "Sistem tidak menyertakan integrasi gateway pembayaran otomatis berizin kliring (seperti Midtrans/Xendit); verifikasi transaksi bertumpu pada konfirmasi staf keuangan.")
    add_bullet("Batasan Jangkauan Peta", "Visualisasi pemetaan area jaringan dibatasi pada zona operasional aktif TRICORE DATA MEDIA: Purwokerto Timur, Purwokerto Wetan, dan Sokaraja.")

    # --- 5. Business Requirement Specification ---
    add_section_title("5. Business Requirement Specification (BRS)")
    add_subsection_title("5.1. Business Goals")
    add_bullet("Efisiensi Operasional Terpadu", "Menghapus ketergantungan pada lembar sebar manual sehingga beban kerja administrasi harian dapat diminimalkan.")
    add_bullet("Transparansi Finansial", "Menyajikan data arus kas masuk dan histori tagihan yang akurat tanpa risiko tumpang tindih data.")
    add_bullet("Kemudahan Akuisisi Pelanggan", "Mempersingkat alur registrasi pelanggan baru melalui integrasi form website TRINET-BILL.")

    add_subsection_title("5.2. Stakeholders")
    
    # Stakeholder Table
    st_table = doc.add_table(rows=5, cols=3)
    st_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    headers = ["No", "Stakeholder / Aktor", "Peran dan Tanggung Jawab dalam TRINET-BILL"]
    for j, h in enumerate(headers):
        cell = st_table.rows[0].cells[j]
        set_cell_shading(cell, "1E3A8A")
        set_cell_margins(cell, top=120, bottom=120, left=120, right=120)
        p = cell.paragraphs[0]
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        r = p.add_run(h)
        r.bold = True
        r.font.name = 'Times New Roman'
        r.font.size = Pt(11)
        r.font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)

    st_data = [
        ("1", "Super Administrator / Manajemen", "Mengontrol penuh konfigurasi tarif paket, meninjau laporan keuangan bulanan, mengaudit riwayat transaksi, dan memantau performa bisnis."),
        ("2", "Staf Administrasi & Billing", "Menerbitkan invoice bulanan secara massal, memverifikasi bukti transfer pembayaran, mengubah status pelanggan, dan mencetak faktur PDF."),
        ("3", "Mitra / Teknisi Lapangan", "Mencatat status pemasangan fisik baru, memperbarui alokasi titik tiang ODP, serta mengelola pencatatan alamat IP perangkat pelanggan."),
        ("4", "Pelanggan & Publik (End User)", "Mengakses katalog paket internet, memverifikasi jangkauan area fiber optic, melakukan pendaftaran pemasangan baru, dan mengecek tagihan secara mandiri.")
    ]

    col_widths = [Inches(0.6), Inches(2.2), Inches(3.4)]
    for row_idx, data in enumerate(st_data, start=1):
        row = st_table.rows[row_idx]
        bg_color = "F8FAFC" if row_idx % 2 == 1 else "FFFFFF"
        for col_idx, text in enumerate(data):
            cell = row.cells[col_idx]
            cell.width = col_widths[col_idx]
            set_cell_shading(cell, bg_color)
            set_cell_margins(cell, top=80, bottom=80, left=100, right=100)
            set_cell_border(cell, top={"color":"CBD5E1"}, bottom={"color":"CBD5E1"}, left={"color":"CBD5E1"}, right={"color":"CBD5E1"})
            p = cell.paragraphs[0]
            p.paragraph_format.line_spacing = 1.15
            p.alignment = WD_ALIGN_PARAGRAPH.CENTER if col_idx == 0 else WD_ALIGN_PARAGRAPH.LEFT
            r = p.add_run(text)
            r.font.name = 'Times New Roman'
            r.font.size = Pt(10.5)

    add_subsection_title("5.3. Business Process Description (AS-IS vs TO-BE)")
    add_body_paragraph(
        "Penerapan TRINET-BILL merevolusi proses bisnis konvensional pada TRICORE DATA MEDIA menjadi alur kerja berbasis web yang terstruktur:"
    )

    # AS-IS vs TO-BE Table
    proc_table = doc.add_table(rows=5, cols=3)
    proc_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    p_headers = ["Dimensi Proses", "Proses Saat Ini (AS-IS)", "Proses Diusulkan (TO-BE TRINET-BILL)"]
    for j, h in enumerate(p_headers):
        cell = proc_table.rows[0].cells[j]
        set_cell_shading(cell, "1E3A8A")
        set_cell_margins(cell, top=120, bottom=120, left=120, right=120)
        p = cell.paragraphs[0]
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        r = p.add_run(h)
        r.bold = True
        r.font.name = 'Times New Roman'
        r.font.size = Pt(11)
        r.font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)

    proc_data = [
        ("Registrasi Pemasangan", "Calon pelanggan menghubungi staf secara personal melalui pesan chat tanpa format baku; pengecekan lokasi memakan waktu lama.", "Calon pelanggan mendaftar melalui formulir website TRINET-BILL yang langsung terintegrasi dengan validasi peta coverage area."),
        ("Pencatatan Data Teknis", "Data nomor tiang ODP dan alamat IP dicatat terpisah di buku/catatan teknisi dan rentan hilang saat pergantian personel.", "Semua atribut ODP dan alokasi IP terhubung langsung dengan profil pelanggan di pangkalan data terpusat TRINET-BILL."),
        ("Penerbitan & Cek Tagihan", "Penagihan dilakukan manual dengan menyusun pesan satu per satu; pelanggan tidak bisa mengecek nominal secara mandiri.", "TRINET-BILL menerbitkan invoice bulanan secara serentak (bulk billing) dan menyediakan portal cek tagihan mandiri berbasis ID Pelanggan."),
        ("Verifikasi Pembayaran", "Pencocokan bukti transfer dilakukan manual pada rekening bank sehingga pembaruan status lunas memakan waktu 1–2 hari.", "Staf administrasi cukup mengklik tombol verifikasi di dasbor TRINET-BILL; sistem langsung menerbitkan faktur lunas dan memperbarui status langganan.")
    ]

    p_col_widths = [Inches(1.5), Inches(2.3), Inches(2.4)]
    for row_idx, data in enumerate(proc_data, start=1):
        row = proc_table.rows[row_idx]
        bg_color = "F8FAFC" if row_idx % 2 == 1 else "FFFFFF"
        for col_idx, text in enumerate(data):
            cell = row.cells[col_idx]
            cell.width = p_col_widths[col_idx]
            set_cell_shading(cell, bg_color)
            set_cell_margins(cell, top=80, bottom=80, left=100, right=100)
            set_cell_border(cell, top={"color":"CBD5E1"}, bottom={"color":"CBD5E1"}, left={"color":"CBD5E1"}, right={"color":"CBD5E1"})
            p = cell.paragraphs[0]
            p.paragraph_format.line_spacing = 1.15
            p.alignment = WD_ALIGN_PARAGRAPH.LEFT
            r = p.add_run(text)
            r.font.name = 'Times New Roman'
            r.font.size = Pt(10)

    add_subsection_title("5.4. High-Level Business Requirements")
    add_bullet("HLBR-01", "TRINET-BILL harus menyediakan mekanisme penomoran pelanggan otomatis dengan format unik terstruktur (misal: TDM-2601).")
    add_bullet("HLBR-02", "TRINET-BILL harus mampu menghasilkan invoice bulanan serentak untuk seluruh pelanggan berstatus aktif dengan satu klik (batch billing).")
    add_bullet("HLBR-03", "TRINET-BILL harus menyediakan pengelolaan status pelanggan berjenjang: Aktif, Pending, dan Terisolir.")
    add_bullet("HLBR-04", "TRINET-BILL harus menyajikan dasbor analitik real-time yang menampilkan total pendapatan, tagihan belum terbayar, dan pertumbuhan pelanggan.")
    add_bullet("HLBR-05", "Antarmuka publik TRINET-BILL harus responsif, ringan, dan mudah diakses melalui perangkat seluler maupun desktop.")

    add_subsection_title("5.5. Business Rules")
    add_bullet("BR-01 (Siklus Penagihan)", "Tagihan diterbitkan pada tanggal 1 setiap bulan kalender dan memiliki batas pembayaran (jatuh tempo) pada tanggal 20 bulan berjalan.")
    add_bullet("BR-02 (Kebijakan Isolir Layanan)", "Pelanggan yang belum melakukan pembayaran hingga melewati batas tanggal jatuh tempo otomatis berubah statusnya menjadi 'Terisolir'.")
    add_bullet("BR-03 (Alokasi Unik Perangkat)", "Satu alamat IP statis dan port ODP hanya boleh diasosiasikan dengan tepat satu ID Pelanggan aktif.")
    add_bullet("BR-04 (Validasi Pembayaran)", "Status invoice berubah menjadi 'Lunas' hanya setelah staf administrasi/keuangan mengonfirmasi mutasi dana pada rekening bank.")

    # --- 6. Methodology ---
    add_section_title("6. Methodology (Metodologi Pengembangan)")
    add_subsection_title("6.1. Metode Pengembangan Perangkat Lunak")
    add_body_paragraph(
        "Pengembangan TRINET-BILL mengadopsi metodologi Agile Software Development dengan kerangka kerja Scrum. Pendekatan ini memungkinkan tim untuk beradaptasi secara cepat terhadap evaluasi kebutuhan bisnis TRICORE DATA MEDIA melalui iterasi Sprint 2 mingguan."
    )

    add_subsection_title("6.2. Perangkat dan Lingkungan Pengembangan")
    add_bullet("Backend Framework", "PHP 8.5 dengan Framework Laravel 12 (Arsitektur Model-View-Controller).")
    add_bullet("Frontend & Styling", "Blade Templating Engine, Tailwind CSS v4, Vite 8, dan Alpine.js.")
    add_bullet("Manajemen Basis Data", "Relational Database Management System (RDBMS MySQL / SQLite) dengan skema migration dan seeder data.")
    add_bullet("Manajemen Repositori & Kontainer", "Git, GitHub Repository, Docker Container, dan Nginx Web Server.")

    add_subsection_title("6.3. Tahapan Pengerjaan Proyek")
    add_bullet("Tahap 1 - Analisis Kebutuhan", "Wawancara dengan mitra TRICORE DATA MEDIA, observasi alur kerja ISP, serta penyusunan dokumen BRS dan SRS.")
    add_bullet("Tahap 2 - Perancangan Desain", "Merancang skema relasi basis data (ERD), diagram arsitektur perangkat lunak (UML), serta mockup antarmuka TRINET-BILL.")
    add_bullet("Tahap 3 - Implementasi (Sprint 1 - 3)", "Mengembangkan modul inti autentikasi, manajemen paket, manajemen ODP/IP, mesin billing otomatis, dan portal publik.")
    add_bullet("Tahap 4 - Pengujian Sistem", "Menjalankan pengujian fungsional berbasis Black-Box Testing dan pengujian penerimaan pengguna (User Acceptance Testing / UAT).")
    add_bullet("Tahap 5 - Penyebaran & Evaluasi", "Konfigurasi server cloud, deployment kontainer Docker, migrasi basis data, dan finalisasi laporan akhir.")

    # --- 7. Proposed System Overview ---
    add_section_title("7. Proposed System Overview (Solusi TRINET-BILL)")
    add_body_paragraph(
        "TRINET-BILL (TRIcore Network Billing) dirancang sebagai solusi berbasis web terpadu yang memadukan portal informasi publik dan sistem manajemen administratif ke dalam satu arsitektur modular yang kokoh:"
    )
    add_bullet("Website Publik TRINET-BILL", "Menyajikan landing page modern, katalog paket internet fiber optic, peta interaktif coverage area Purwokerto dan Sokaraja, formulir pendaftaran pelanggan baru online, serta modul pengecekan tagihan mandiri secara instan.")
    add_bullet("Portal Terproteksi Admin & Mitra TRINET-BILL", "Dasbor kendali untuk mengelola data master pelanggan, alokasi ODP dan IP address, mesin penerbitan tagihan bulanan otomatis, validasi mutasi pembayaran, serta ekspor kuitansi tagihan format PDF.")

    add_body_paragraph(
        "Mekanisme operasional TRINET-BILL beroperasi melalui alur Input - Proses - Output (IPO) berikut:"
    )
    add_bullet("Input Sistem", "Data registrasi pelanggan baru, data master paket internet, penyesuaian biaya instalasi/diskon, bukti mutasi transfer, dan nomor tiang ODP.")
    add_bullet("Proses Sistem", "Generate ID Pelanggan otomatis (TDM-XXXX), kalkulasi total tagihan bulanan, enkripsi kata sandi, verifikasi hak akses peran, dan pembuatan dokumen PDF.")
    add_bullet("Output Sistem", "Faktur tagihan digital (PDF), kuitansi pelunasan resmi, format pesan notifikasi WhatsApp, dan grafik analitik keuangan usaha.")

    # --- 8. Project Deliverables ---
    add_section_title("8. Project Deliverables")
    add_body_paragraph(
        "Artefak dan luaran yang dihasilkan pada akhir pelaksanaan Computing Project TRINET-BILL meliputi:"
    )
    
    # Deliverables Table
    del_table = doc.add_table(rows=6, cols=3)
    del_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    d_headers = ["No", "Kategori Luaran", "Deskripsi Artefak yang Dihasilkan"]
    for j, h in enumerate(d_headers):
        cell = del_table.rows[0].cells[j]
        set_cell_shading(cell, "1E3A8A")
        set_cell_margins(cell, top=120, bottom=120, left=120, right=120)
        p = cell.paragraphs[0]
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        r = p.add_run(h)
        r.bold = True
        r.font.name = 'Times New Roman'
        r.font.size = Pt(11)
        r.font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)

    del_data = [
        ("1", "Dokumentasi Rekayasa Perangkat Lunak", "Dokumen Software Requirement Specification (SRS), Software Design Document (SDD), dan laporan skenario User Acceptance Testing (UAT)."),
        ("2", "Aplikasi Web Siap Produksi (TRINET-BILL)", "Website aplikasi TRINET-BILL fungsional yang mencakup landing page publik dan portal manajemen admin/mitra yang terpasang di peladen cloud."),
        ("3", "Paket Repositori Kode Sumber & Basis Data", "Repositori kode sumber Git lengkap dengan skrip migrasi database, seeder data demo, dan berkas Dockerfile."),
        ("4", "Buku Petunjuk Pengguna (User Manual)", "Panduan pengoperasian sistem langkah-demi-langkah bagi Administrator dan Teknisi Lapangan TRINET-BILL."),
        ("5", "Laporan Akhir & Materi Presentasi", "Naskah Laporan Akhir Computing Project, salindia presentasi sidang proposal/akhir, poster ilmiah, dan video demonstrasi sistem.")
    ]

    d_col_widths = [Inches(0.6), Inches(2.2), Inches(3.4)]
    for row_idx, data in enumerate(del_data, start=1):
        row = del_table.rows[row_idx]
        bg_color = "F8FAFC" if row_idx % 2 == 1 else "FFFFFF"
        for col_idx, text in enumerate(data):
            cell = row.cells[col_idx]
            cell.width = d_col_widths[col_idx]
            set_cell_shading(cell, bg_color)
            set_cell_margins(cell, top=80, bottom=80, left=100, right=100)
            set_cell_border(cell, top={"color":"CBD5E1"}, bottom={"color":"CBD5E1"}, left={"color":"CBD5E1"}, right={"color":"CBD5E1"})
            p = cell.paragraphs[0]
            p.paragraph_format.line_spacing = 1.15
            p.alignment = WD_ALIGN_PARAGRAPH.CENTER if col_idx == 0 else WD_ALIGN_PARAGRAPH.LEFT
            r = p.add_run(text)
            r.font.name = 'Times New Roman'
            r.font.size = Pt(10)

    # --- 9. Project Timeline ---
    add_section_title("9. Project Timeline")
    add_body_paragraph(
        "Rencana pelaksanaan proyek TRINET-BILL dirancang untuk jangka waktu 16 minggu kerja dengan pembagian tahapan dan penanggung jawab sebagai berikut:"
    )

    # Timeline Table
    time_table = doc.add_table(rows=7, cols=7)
    time_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    t_headers = ["No", "Tahapan Kegiatan", "Bln 1", "Bln 2", "Bln 3", "Bln 4", "PIC"]
    for j, h in enumerate(t_headers):
        cell = time_table.rows[0].cells[j]
        set_cell_shading(cell, "1E3A8A")
        set_cell_margins(cell, top=100, bottom=100, left=60, right=60)
        p = cell.paragraphs[0]
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        r = p.add_run(h)
        r.bold = True
        r.font.name = 'Times New Roman'
        r.font.size = Pt(10)
        r.font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)

    timeline_data = [
        ("1", "Analisis Kebutuhan & BRS/SRS TRINET-BILL", "V", "", "", "", "Kanasya"),
        ("2", "Perancangan UI/UX & Arsitektur Basis Data", "", "V", "", "", "Icha & Kanasya"),
        ("3", "Pengembangan Backend & Frontend TRINET-BILL", "", "V", "V", "", "Arsya & Icha"),
        ("4", "Integrasi Billing Engine & WhatsApp Gateway", "", "", "V", "", "Arsya & Afrizal"),
        ("5", "Pengujian Fungsional & UAT TRINET-BILL", "", "", "", "V", "Agnes & Amel"),
        ("6", "Deployment Cloud & Finalisasi Laporan Akhir", "", "", "", "V", "Semua Anggota Tim")
    ]

    t_col_widths = [Inches(0.4), Inches(2.7), Inches(0.5), Inches(0.5), Inches(0.5), Inches(0.5), Inches(1.1)]
    for row_idx, data in enumerate(timeline_data, start=1):
        row = time_table.rows[row_idx]
        bg_color = "F8FAFC" if row_idx % 2 == 1 else "FFFFFF"
        for col_idx, text in enumerate(data):
            cell = row.cells[col_idx]
            cell.width = t_col_widths[col_idx]
            set_cell_shading(cell, bg_color)
            set_cell_margins(cell, top=60, bottom=60, left=60, right=60)
            set_cell_border(cell, top={"color":"CBD5E1"}, bottom={"color":"CBD5E1"}, left={"color":"CBD5E1"}, right={"color":"CBD5E1"})
            p = cell.paragraphs[0]
            p.paragraph_format.line_spacing = 1.15
            p.alignment = WD_ALIGN_PARAGRAPH.CENTER if col_idx in [0, 2, 3, 4, 5] else WD_ALIGN_PARAGRAPH.LEFT
            r = p.add_run(text)
            r.font.name = 'Times New Roman'
            r.font.size = Pt(9.5)

    # --- 10. Team Members and Roles ---
    add_section_title("10. Team Members and Roles")
    add_body_paragraph(
        "Struktur organisasi tim pengembang TRINET-BILL dirancang untuk memastikan setiap tahapan rekayasa perangkat lunak berjalan terarah dan akuntabel:"
    )

    # Team Roles Table
    team_table = doc.add_table(rows=7, cols=4)
    team_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    r_headers = ["No", "Nama Mahasiswa", "NIM", "Peran / Tanggung Jawab Utama"]
    for j, h in enumerate(r_headers):
        cell = team_table.rows[0].cells[j]
        set_cell_shading(cell, "1E3A8A")
        set_cell_margins(cell, top=120, bottom=120, left=100, right=100)
        p = cell.paragraphs[0]
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        r = p.add_run(h)
        r.bold = True
        r.font.name = 'Times New Roman'
        r.font.size = Pt(10.5)
        r.font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)

    team_data = [
        ("1", "Valisha Atthalia Naura Irfan (Icha)", "2311102160", "Project Manager & Front-End Developer\nMemimpin manajemen proyek TRINET-BILL dan merancang antarmuka pengguna responsif (Blade & Tailwind CSS)."),
        ("2", "Amelia Azmi (Amel)", "2311102152", "Project Manager & Business Analyst\nMengkoordinasikan jadwal kerja, menjembatani komunikasi mitra, dan menyelaraskan kebutuhan bisnis operasional."),
        ("3", "Arsya Fathiha Rahman", "2311102152", "Back-End Developer\nMembangun logika inti aplikasi berbasis Laravel 12, skema basis data, mesin billing otomatis, serta arsitektur backend."),
        ("4", "Kanasya Abdi Aziz", "2311102140", "System Analyst\nMenyusun spesifikasi kebutuhan sistem (BRS/SRS), perancangan arsitektur UML, ERD, dan diagram alur proses bisnis."),
        ("5", "Agnes Refilina Fiska", "2311102126", "System / Software Quality Assurance Tester\nMenyusun skenario pengujian, mengeksekusi Black-Box Testing, memverifikasi kepatuhan aturan bisnis, dan mengawal UAT."),
        ("6", "Afrizal Dwi Nugraha", "2311102136", "Business Analyst & Network Specialist\nMenganalisis alokasi teknis jaringan optik (ODP & IP) serta menyusun strategi pemetaan cakupan wilayah (coverage area).")
    ]

    r_col_widths = [Inches(0.5), Inches(2.2), Inches(1.1), Inches(2.4)]
    for row_idx, data in enumerate(team_data, start=1):
        row = team_table.rows[row_idx]
        bg_color = "F8FAFC" if row_idx % 2 == 1 else "FFFFFF"
        for col_idx, text in enumerate(data):
            cell = row.cells[col_idx]
            cell.width = r_col_widths[col_idx]
            set_cell_shading(cell, bg_color)
            set_cell_margins(cell, top=80, bottom=80, left=80, right=80)
            set_cell_border(cell, top={"color":"CBD5E1"}, bottom={"color":"CBD5E1"}, left={"color":"CBD5E1"}, right={"color":"CBD5E1"})
            p = cell.paragraphs[0]
            p.paragraph_format.line_spacing = 1.15
            p.alignment = WD_ALIGN_PARAGRAPH.CENTER if col_idx in [0, 2] else WD_ALIGN_PARAGRAPH.LEFT
            r = p.add_run(text)
            r.font.name = 'Times New Roman'
            r.font.size = Pt(10)

    # --- 11. References ---
    add_section_title("11. References (Daftar Pustaka)")
    references = [
        "Dennis, A., Wixom, B. H., & Tegarden, D. (2021). Systems Analysis and Design: An Object-Oriented Approach with UML (6th ed.). John Wiley & Sons.",
        "Larman, C. (2018). Applying UML and Patterns: An Introduction to Object-Oriented Analysis and Design and Iterative Development (3rd ed.). Pearson Education.",
        "Otte, S., & Stauffer, M. (2023). Laravel: Up & Running: A Framework for Building Modern PHP Apps (3rd ed.). O’Reilly Media.",
        "Pressman, R. S., & Maxim, B. R. (2020). Software Engineering: A Practitioner’s Approach (9th ed.). McGraw-Hill Education.",
        "Rahman, A., & Santoso, H. (2024). Rancang Bangun Sistem Informasi Billing dan Manajemen Pelanggan pada Penyedia Jasa Internet Berbasis Web. Jurnal Teknologi Informasi dan Rekayasa Komputer, 12(2), 145–158.",
        "Sommerville, I. (2021). Software Engineering (10th ed.). Pearson.",
        "Tanenbaum, A. S., & Wetherall, D. J. (2021). Computer Networks (6th ed.). Pearson.",
        "Utomo, P., & Nugroho, A. (2023). Otomasi Pengelolaan Data Pelanggan dan Tagihan ISP Menggunakan Framework PHP dan Notifikasi Instan. Jurnal Rekayasa Sistem dan Industri, 10(1), 33–42."
    ]

    for ref in references:
        p = doc.add_paragraph()
        p.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
        p.paragraph_format.line_spacing = 1.5
        p.paragraph_format.left_indent = Inches(0.5)
        p.paragraph_format.first_line_indent = Inches(-0.5)
        p.paragraph_format.space_after = Pt(6)
        r = p.add_run(ref)
        r.font.name = 'Times New Roman'
        r.font.size = Pt(11)

    # Save document
    output_path = "Proposal_Computing_Project_Layanan_WiFi_Tricore.docx"
    doc.save(output_path)
    print(f"Document successfully created: {output_path}")

if __name__ == "__main__":
    create_proposal_document()
