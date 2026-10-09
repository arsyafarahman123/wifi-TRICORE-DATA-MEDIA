import os
import docx
from docx import Document
from docx.shared import Inches, Pt, RGBColor, Cm
from docx.enum.text import WD_ALIGN_PARAGRAPH, WD_TAB_ALIGNMENT, WD_TAB_LEADER
from docx.enum.table import WD_TABLE_ALIGNMENT
from docx.oxml import OxmlElement, parse_xml
from docx.oxml.ns import nsdecls, qn

W_NS = "http://schemas.openxmlformats.org/wordprocessingml/2006/main"

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
    shd = parse_xml(f'<w:shd {nsdecls("w")} w:fill="{color_hex}"/>')
    cell._tc.get_or_add_tcPr().append(shd)

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

def add_clean_footer_pagenum(section, is_roman=False, start_num=1):
    sectPr = section._sectPr
    
    # Remove existing pgNumType if any
    for child in list(sectPr):
        if child.tag.endswith('pgNumType'):
            sectPr.remove(child)
            
    fmt = "romanLower" if is_roman else "decimal"
    pgNumType = parse_xml(f'<w:pgNumType {nsdecls("w")} w:fmt="{fmt}" w:start="{start_num}"/>')
    sectPr.append(pgNumType)

    # Configure footer
    footer = section.footer
    footer.is_linked_to_previous = False
    p = footer.paragraphs[0]
    p.text = ""
    p.alignment = WD_ALIGN_PARAGRAPH.RIGHT
    
    # Insert field with fallback placeholder text
    init_val = "i" if is_roman else "1"
    fld_xml = f'''
    <w:fldSimple {nsdecls("w")} w:instr="PAGE">
        <w:r>
            <w:rPr>
                <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/>
                <w:sz w:val="20"/>
            </w:rPr>
            <w:t>{init_val}</w:t>
        </w:r>
    </w:fldSimple>
    '''
    p._p.append(parse_xml(fld_xml))

def add_toc_line(doc, title, page_str, is_bold=False, level=1):
    p = doc.add_paragraph()
    p.paragraph_format.line_spacing = 1.25
    p.paragraph_format.space_after = Pt(3)
    p.paragraph_format.space_before = Pt(1)
    
    # Indentation by level
    if level == 2:
        p.paragraph_format.left_indent = Inches(0.25)
    elif level == 3:
        p.paragraph_format.left_indent = Inches(0.5)
        
    # Tab stop at right margin with dot leader
    tab_stops = p.paragraph_format.tab_stops
    tab_stop = tab_stops.add_tab_stop(Inches(5.5), WD_TAB_ALIGNMENT.RIGHT, WD_TAB_LEADER.DOTS)

    # Title run
    r_title = p.add_run(title)
    r_title.font.name = 'Times New Roman'
    r_title.font.size = Pt(11)
    r_title.bold = is_bold
    
    # Tab + Page Number run
    r_tab = p.add_run(f"\t{page_str}")
    r_tab.font.name = 'Times New Roman'
    r_tab.font.size = Pt(11)
    r_tab.bold = is_bold
    return p

def create_proposal_docx():
    doc = Document()
    
    # Margin A4: Top 3cm, Left 4cm, Bottom 3cm, Right 3cm
    for s in doc.sections:
        s.page_width = Cm(21.0)
        s.page_height = Cm(29.7)
        s.top_margin = Cm(3.0)
        s.bottom_margin = Cm(3.0)
        s.left_margin = Cm(4.0)
        s.right_margin = Cm(3.0)
        s.different_first_page_header_footer = True
        
    normal_style = doc.styles['Normal']
    normal_style.font.name = 'Times New Roman'
    normal_style.font.size = Pt(12)
    normal_style.font.color.rgb = RGBColor(0x1F, 0x24, 0x2E)
    normal_style.paragraph_format.line_spacing = 1.5
    normal_style.paragraph_format.space_after = Pt(4)
    normal_style.paragraph_format.space_before = Pt(0)

    # ====================================================
    # 1. COVER PAGE (SECTION 1) - No Page Number
    # ====================================================
    sec1 = doc.sections[0]
    sec1.different_first_page_header_footer = True
    sec1.first_page_footer.is_linked_to_previous = False
    
    p_header = doc.add_paragraph()
    p_header.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_header.paragraph_format.line_spacing = 1.15
    p_header.paragraph_format.space_after = Pt(2)
    r1 = p_header.add_run("COMPUTING PROJECT\nPROPOSAL")
    r1.bold = True
    r1.font.size = Pt(14)

    p_title = doc.add_paragraph()
    p_title.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_title.paragraph_format.space_before = Pt(12)
    p_title.paragraph_format.space_after = Pt(14)
    p_title.paragraph_format.line_spacing = 1.15
    r_title = p_title.add_run("TRINET-BILL: TRICORE NETWORK BILLING — PENGEMBANGAN SISTEM INFORMASI MANAJEMEN OPERASIONAL DAN PENAGIHAN INTERNET SERVICE PROVIDER BERBASIS WEB PADA TRICORE DATA MEDIA PURWOKERTO")
    r_title.bold = True
    r_title.font.size = Pt(12.5)

    p_logo = doc.add_paragraph()
    p_logo.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_logo.paragraph_format.space_before = Pt(4)
    p_logo.paragraph_format.space_after = Pt(10)
    if os.path.exists("telkom_logo.png"):
        p_logo.add_run().add_picture("telkom_logo.png", width=Inches(2.0))
    else:
        r_logo = p_logo.add_run("[ LOGO UNIVERSITAS TELKOM ]")
        r_logo.bold = True

    p_pm = doc.add_paragraph()
    p_pm.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_pm.paragraph_format.line_spacing = 1.15
    p_pm.paragraph_format.space_before = Pt(4)
    p_pm.paragraph_format.space_after = Pt(2)
    r_pm_h = p_pm.add_run("Project Manager:\n")
    r_pm_h.bold = True
    r_pm_h.font.size = Pt(11)
    
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
        set_cell_margins(c1, top=12, bottom=12, left=0, right=0)
        set_cell_margins(c2, top=12, bottom=12, left=0, right=0)

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
        set_cell_margins(c1, top=10, bottom=10, left=0, right=0)
        set_cell_margins(c2, top=10, bottom=10, left=0, right=0)

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

    p_inst = doc.add_paragraph()
    p_inst.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_inst.paragraph_format.line_spacing = 1.15
    p_inst.paragraph_format.space_before = Pt(12)
    p_inst.paragraph_format.space_after = Pt(0)
    r_inst = p_inst.add_run("PROGRAM STUDI S-1 TEKNIK INFORMATIKA\nDIREKTORAT KAMPUS PURWOKERTO – UNIVERSITAS TELKOM\nMARET 2026")
    r_inst.bold = True
    r_inst.font.size = Pt(12)

    # ====================================================
    # 2. FRONT MATTER (SECTION 2) - Roman Lower: i, ii
    # ====================================================
    sec2 = doc.add_section(docx.enum.section.WD_SECTION.NEW_PAGE)
    sec2.top_margin = Cm(3.0)
    sec2.bottom_margin = Cm(3.0)
    sec2.left_margin = Cm(4.0)
    sec2.right_margin = Cm(3.0)
    sec2.different_first_page_header_footer = False
    add_clean_footer_pagenum(sec2, is_roman=True, start_num=1)

    # --- Executive Summary (Page i) ---
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
        "Kebutuhan masyarakat terhadap koneksi internet pita lebar (broadband) berbasis serat optik di kawasan semi-perkotaan menuntut penyedia jasa internet (ISP) lokal memiliki tata kelola operasional yang terintegrasi, tangkas, dan minim kekeliruan data. TRICORE DATA MEDIA, sebagai entitas ISP yang melayani area Purwokerto Timur, Purwokerto Wetan, dan Sokaraja, saat ini menghadapi kendala operasional yang signifikan. Proses administrasi yang meliputi pendaftaran pelanggan, pemetaan infrastruktur titik Optical Distribution Point (ODP), pencatatan alokasi alamat IP, hingga verifikasi mutasi pembayaran bulanan masih dilakukan secara manual dan terpisah-pisah. Pola kerja tersebut memicu keterlambatan penerbitan faktur tagihan (invoice), kekeliruan status isolir pelanggan yang menunggak, serta lambatnya respons terhadap calon pelanggan baru."
    )

    p_ex_2 = doc.add_paragraph()
    p_ex_2.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
    p_ex_2.paragraph_format.line_spacing = 1.5
    p_ex_2.paragraph_format.space_after = Pt(8)
    p_ex_2.add_run(
        "Sebagai solusi terpadu, proyek ini mengusulkan pengembangan TRINET-BILL: TRIcore Network Billing, sebuah sistem informasi manajemen operasional dan penagihan ISP berbasis web modern. TRINET-BILL menyatukan seluruh proses bisnis inti ke dalam satu platform terpusat, mencakup portal publik interaktif (pemeriksaan jangkauan fiber optic, simulasi paket langganan 15–50 Mbps, formulir registrasi online, dan form cek tagihan mandiri), modul penagihan berkala otomatis (automated billing engine), integrasi gateway notifikasi WhatsApp bisnis, serta portal administratif terproteksi untuk pengelolaan data master pelanggan, rekonsiliasi mutasi kas, dan pemantauan keuangan secara real-time."
    )

    p_ex_3 = doc.add_paragraph()
    p_ex_3.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
    p_ex_3.paragraph_format.line_spacing = 1.5
    p_ex_3.paragraph_format.space_after = Pt(14)
    p_ex_3.add_run(
        "Pengembangan sistem dilaksanakan menggunakan metodologi Agile Software Development kerangka kerja Scrum, memanfaatkan tumpukan teknologi Laravel 12 (PHP 8.5), Tailwind CSS v4, Vite 8, arsitektur basis data relasional, dan kontainerisasi Docker. Luaran proyek mencakup dokumen formal rekayasa perangkat lunak (SRS, SDD, UAT, User Guide), aplikasi web siap produksi (production-ready), serta laporan akhir capstone. Implementasi TRINET-BILL ditargetkan mampu memangkas waktu rekonsiliasi tagihan hingga 80%, mengeliminasi kesalahan alokasi data teknis ODP, dan meningkatkan kepuasan pelanggan secara berkelanjutan."
    )

    # --- Table of Content Page (Page ii) ---
    doc.add_page_break()

    p_toc_h = doc.add_paragraph()
    p_toc_h.paragraph_format.space_before = Pt(0)
    p_toc_h.paragraph_format.space_after = Pt(14)
    r_toc_h = p_toc_h.add_run("Table of Content")
    r_toc_h.bold = True
    r_toc_h.font.size = Pt(14)
    r_toc_h.font.color.rgb = RGBColor(0x1E, 0x3A, 0x8A)

    # Dotted line TOC Items with exact accurate page numbers!
    add_toc_line(doc, "Executive Summary", "i", is_bold=True, level=1)
    add_toc_line(doc, "Table of Content", "ii", is_bold=True, level=1)
    add_toc_line(doc, "1. Background (Latar Belakang)", "1", is_bold=True, level=1)
    add_toc_line(doc, "2. Problem Statement (Rumusan Masalah)", "2", is_bold=True, level=1)
    add_toc_line(doc, "3. Objectives (Tujuan Proyek)", "2", is_bold=True, level=1)
    add_toc_line(doc, "4. Scope and Limitations (Ruang Lingkup dan Batasan)", "3", is_bold=True, level=1)
    add_toc_line(doc, "5. Business Requirement Specification (BRS)", "3", is_bold=True, level=1)
    add_toc_line(doc, "5.1. Business Goals", "3", is_bold=False, level=2)
    add_toc_line(doc, "5.2. Stakeholders", "4", is_bold=False, level=2)
    add_toc_line(doc, "5.3. Business Process Description (AS-IS dan TO-BE)", "4", is_bold=False, level=2)
    add_toc_line(doc, "5.4. High-Level Business Requirements", "5", is_bold=False, level=2)
    add_toc_line(doc, "5.5. Business Rules", "5", is_bold=False, level=2)
    add_toc_line(doc, "6. Methodology (Metodologi Pengembangan)", "6", is_bold=True, level=1)
    add_toc_line(doc, "6.1. Pendekatan Agile / Scrum", "6", is_bold=False, level=2)
    add_toc_line(doc, "6.2. Perangkat dan Lingkungan Pengembangan", "6", is_bold=False, level=2)
    add_toc_line(doc, "6.3. Tahapan Pengerjaan Proyek", "6", is_bold=False, level=2)
    add_toc_line(doc, "7. Proposed System Overview (Solusi TRINET-BILL)", "7", is_bold=True, level=1)
    add_toc_line(doc, "8. Project Deliverables", "8", is_bold=True, level=1)
    add_toc_line(doc, "9. Project Timeline", "8", is_bold=True, level=1)
    add_toc_line(doc, "10. Team Members and Roles", "9", is_bold=True, level=1)
    add_toc_line(doc, "11. References (Daftar Pustaka)", "10", is_bold=True, level=1)

    # ====================================================
    # 3. MAIN BODY (SECTION 3) - Arabic Numbers: 1, 2, 3...
    # ====================================================
    sec3 = doc.add_section(docx.enum.section.WD_SECTION.NEW_PAGE)
    sec3.top_margin = Cm(3.0)
    sec3.bottom_margin = Cm(3.0)
    sec3.left_margin = Cm(4.0)
    sec3.right_margin = Cm(3.0)
    sec3.different_first_page_header_footer = False
    add_clean_footer_pagenum(sec3, is_roman=False, start_num=1)

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

    # --- 1. Background (Page 1) ---
    add_section_title("1. Background (Latar Belakang)")
    add_body_paragraph(
        "Kebutuhan akses internet pita lebar (broadband) berkecepatan tinggi kini telah menjadi kebutuhan mendasar dalam menunjang kegiatan operasional bisnis, pendidikan, dan aktivitas harian masyarakat. Di kawasan semi-perkotaan, kehadiran penyedia layanan internet (Internet Service Provider / ISP) lokal sangat krusial dalam menyediakan jaringan kabel serat optik (Fiber to the Home / FTTH) hingga ke lingkungan perumahan. Salah satu entitas ISP lokal yang aktif melayani masyarakat di wilayah Kabupaten Banyumas adalah TRICORE DATA MEDIA, dengan wilayah operasional utama di Purwokerto Timur, Purwokerto Wetan, dan Sokaraja."
    )
    add_body_paragraph(
        "Meskipun penetrasi pelanggan terus bertumbuh, tata kelola operasional harian pada TRICORE DATA MEDIA saat ini masih sangat bergantung pada pencatatan manual berbasis lembar sebar (spreadsheet) dan obrolan pesan instan personal yang tidak terstruktur. Pola kerja tersebut memicu serangkaian kendala nyata di lapangan:"
    )
    add_bullet("Kerentanan Galat Rekonsiliasi Keuangan", "Proses pencocokan mutasi transfer bank dengan data ratusan pelanggan dilakukan secara manual setiap tanggal jatuh tempo, memperbesar risiko salah catat status tagihan dan memperlambat penerbitan bukti lunas.")
    add_bullet("Fragmentasi Informasi Teknis Jaringan", "Dokumentasi kapasitas port Optical Distribution Point (ODP) dan pemetaan alamat IP router pelanggan belum tercatat dalam basis data terpadu, menyulitkan teknisi lapangan saat melakukan pemasangan sambungan baru maupun perbaikan kendala koneksi.")
    add_bullet("Keterbatasan Akses Informasi Mandiri", "Calon pelanggan belum memiliki sarana digital untuk memverifikasi ketersediaan jalur fiber optic di alamat mereka, sementara pelanggan aktif harus menanyakan rincian tagihan secara manual kepada petugas administrasi.")
    add_body_paragraph(
        "Guna mengatasi seluruh persoalan tersebut, proyek ini menghadirkan solusi teknologi bernama TRINET-BILL: TRIcore Network Billing. TRINET-BILL dirancang sebagai sistem informasi manajemen operasional dan penagihan berbasis web terpadu yang menghubungkan portal publik, manajemen data master pelanggan, pemetaan ODP/IP, dan otomasi penagihan secara efisien, transparan, dan akuntabel."
    )

    # --- 2. Problem Statement (Page 2) ---
    add_section_title("2. Problem Statement (Rumusan Masalah)")
    add_body_paragraph(
        "Tata kelola data pelanggan, pemetaan titik distribusi fiber optic (ODP), dan rekonsiliasi tagihan bulanan pada TRICORE DATA MEDIA saat ini masih dijalankan secara manual dan terfragmentasi sehingga memperlambat respon layanan dan meningkatkan risiko kekeliruan pencatatan transaksi finansial. Ketiadaan sistem terintegrasi menyebabkan staf administrasi membutuhkan waktu verifikasi mutasi rekening yang lama, penanganan isolir pelanggan menunggak tidak berjalan otomatis, dan data teknis jaringan rentan hilang saat terjadi pergeseran personel tim lapangan."
    )

    # --- 3. Objectives (Page 2) ---
    add_section_title("3. Objectives (Tujuan Proyek)")
    add_body_paragraph(
        "Tujuan pengembangan sistem TRINET-BILL (TRIcore Network Billing) dirumuskan berdasarkan kriteria SMART (Specific, Measurable, Achievable, Relevant, Time-bound):"
    )
    add_bullet("Tujuan Utama Proyek", "Membangun dan mengimplementasikan sistem informasi manajemen operasional dan penagihan internet berbasis web, TRINET-BILL: TRIcore Network Billing, untuk TRICORE DATA MEDIA dalam kurun waktu 16 minggu kerja.")
    add_bullet("Otomasi Penagihan (Automated Billing)", "Memangkas waktu penerbitan tagihan bulanan dan rekonsiliasi pembayaran pelanggan hingga 80% melalui fitur penerbitan invoice massal otomatis pada TRINET-BILL.")
    add_bullet("Sentralisasi Basis Data Pelanggan & ODP", "Menyediakan penyimpanan data terpusat dengan tingkat akurasi 100% untuk data identitas pelanggan, jenis paket langganan (15–50 Mbps), posisi titik ODP, dan alokasi alamat IP.")
    add_bullet("Penyediaan Saluran Mandiri (Self-Service)", "Menyediakan antarmuka publik interaktif pada website TRINET-BILL yang memudahkan calon pelanggan mengecek cakupan wilayah fiber optic serta memungkinkan pelanggan aktif memeriksa status tagihan secara swalayan.")
    add_bullet("Integrasi Notifikasi Transaksi", "Menghubungkan sistem TRINET-BILL dengan format pesan WhatsApp untuk mempercepat konfirmasi bukti bayar dan penyampaian kuitansi digital dalam durasi kurang dari 5 menit.")

    # --- 4. Scope and Limitations (Page 3) ---
    add_section_title("4. Scope and Limitations (Ruang Lingkup dan Batasan)")
    add_subsection_title("4.1. Ruang Lingkup Sistem (In-Scope)")
    add_bullet("Portal Publik TRINET-BILL", "Katalog paket internet (Hemat 15 Mbps, Family 20 Mbps, Favorit 25 Mbps, Turbo 35 Mbps, Ultimate 50 Mbps), peta interaktif cakupan wilayah, form pendaftaran online, dan form cek tagihan mandiri.")
    add_bullet("Portal Terproteksi (Admin & Mitra)", "Otentikasi multi-peran (Super Admin dan Mitra/Teknisi), dasbor analitik metrik performa (omzet, total pelanggan aktif, rasio invoice pending), dan manajemen paket internet.")
    add_bullet("Manajemen Data Pelanggan & Teknis", "Pencatatan data lengkap pelanggan (CRUD), pengelolaan status langganan (Aktif, Pending, Terisolir), serta pencatatan port ODP dan alokasi alamat IP.")
    add_bullet("Sistem Billing & Faktur Digital", "Penerbitan tagihan berkala otomatis, konfirmasi pelunasan, pencetakan bukti bayar/invoice dalam format PDF, dan integrasi tombol notifikasi pesan WhatsApp.")

    add_subsection_title("4.2. Batasan Sistem (Out-of-Scope)")
    add_bullet("Konfigurasi Otomatis RouterOS/OLT", "Sistem TRINET-BILL pada rilis awal ini fokus pada lapisan manajemen bisnis & data dan belum mengeksekusi skrip konfigurasi langsung ke perangkat keras MikroTik/ZTE via API/SNMP.")
    add_bullet("Payment Gateway Perbankan Otomatis", "Sistem tidak menggunakan gateway pembayaran otomatis berizin kliring perbankan; validasi transaksi bertumpu pada konfirmasi staf keuangan terhadap mutasi rekening.")
    add_bullet("Cakupan Geografis Peta", "Data jangkauan jaringan dibatasi pada zona operasional aktif TRICORE DATA MEDIA: Purwokerto Timur, Purwokerto Wetan, dan Sokaraja.")

    # --- 5. Business Requirement Specification (Page 3-5) ---
    add_section_title("5. Business Requirement Specification (BRS)")
    add_subsection_title("5.1. Business Goals")
    add_bullet("Efisiensi Operasional", "Mengeliminasi proses rekapitulasi manual berbasis lembar sebar sehingga beban administrasi harian berkurang secara signifikan.")
    add_bullet("Transparansi Finansial", "Menjamin kepastian pencatatan tagihan dan status layanan yang transparan untuk meminimalkan komplain pelanggan.")
    add_bullet("Akselerasi Pertumbuhan Bisnis", "Mempermudah akuisisi pelanggan baru melalui portal registrasi online yang langsung terhubung ke bagian teknis lapangan.")

    add_subsection_title("5.2. Stakeholders")
    
    st_table = doc.add_table(rows=5, cols=3)
    st_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    headers = ["No", "Stakeholder / Aktor", "Peran dan Tanggung Jawab dalam Sistem"]
    for j, h in enumerate(headers):
        cell = st_table.rows[0].cells[j]
        set_cell_shading(cell, "1E3A8A")
        set_cell_margins(cell, top=100, bottom=100, left=100, right=100)
        p = cell.paragraphs[0]
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        r = p.add_run(h)
        r.bold = True
        r.font.name = 'Times New Roman'
        r.font.size = Pt(11)
        r.font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)

    st_data = [
        ("1", "Super Administrator / Manajemen", "Mengontrol seluruh data sistem, mengelola tarif paket langganan, melihat laporan keuangan bulanan, dan memantau kinerja bisnis."),
        ("2", "Staf Administrasi & Billing", "Menerbitkan invoice bulanan secara massal, memvalidasi bukti pembayaran transfer, memperbarui status pelanggan, dan mencetak faktur PDF."),
        ("3", "Mitra / Teknisi Lapangan", "Mencatat status fisik instalasi, mencatat posisi tiang ODP, serta mengelola alokasi alamat IP router pelanggan."),
        ("4", "Pelanggan & Publik (End User)", "Melihat katalog layanan, memeriksa ketersediaan jaringan di lokasinya, mendaftar sambungan baru, dan mengecek tagihan mandiri.")
    ]

    col_widths = [Inches(0.6), Inches(2.2), Inches(3.4)]
    for row_idx, data in enumerate(st_data, start=1):
        row = st_table.rows[row_idx]
        bg_color = "F8FAFC" if row_idx % 2 == 1 else "FFFFFF"
        for col_idx, text in enumerate(data):
            cell = row.cells[col_idx]
            cell.width = col_widths[col_idx]
            set_cell_shading(cell, bg_color)
            set_cell_margins(cell, top=70, bottom=70, left=80, right=80)
            set_cell_border(cell, top={"color":"CBD5E1"}, bottom={"color":"CBD5E1"}, left={"color":"CBD5E1"}, right={"color":"CBD5E1"})
            p = cell.paragraphs[0]
            p.paragraph_format.line_spacing = 1.15
            p.alignment = WD_ALIGN_PARAGRAPH.CENTER if col_idx == 0 else WD_ALIGN_PARAGRAPH.LEFT
            r = p.add_run(text)
            r.font.name = 'Times New Roman'
            r.font.size = Pt(10)

    add_subsection_title("5.3. Business Process Description (AS-IS vs TO-BE)")
    add_body_paragraph(
        "Penerapan TRINET-BILL mentransformasi proses bisnis operasional konvensional menjadi alur kerja terstruktur berbasis web:"
    )

    proc_table = doc.add_table(rows=5, cols=3)
    proc_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    p_headers = ["Dimensi Proses", "Proses Saat Ini (AS-IS)", "Proses Diusulkan (TO-BE TRINET-BILL)"]
    for j, h in enumerate(p_headers):
        cell = proc_table.rows[0].cells[j]
        set_cell_shading(cell, "1E3A8A")
        set_cell_margins(cell, top=100, bottom=100, left=100, right=100)
        p = cell.paragraphs[0]
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        r = p.add_run(h)
        r.bold = True
        r.font.name = 'Times New Roman'
        r.font.size = Pt(11)
        r.font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)

    proc_data = [
        ("Registrasi Pemasangan", "Calon pelanggan menghubungi staf secara personal melalui pesan instan tanpa format baku; verifikasi lokasi memakan waktu lama.", "Calon pelanggan mendaftar melalui formulir web TRINET-BILL yang langsung terintegrasi dengan validasi peta coverage area."),
        ("Pencatatan Data Teknis", "Data nomor tiang ODP dan alamat IP dicatat terpisah di catatan teknisi dan rentan hilang saat pergantian personel.", "Semua parameter ODP dan alokasi IP terasosiasi langsung dengan ID Pelanggan di pangkalan data terpusat."),
        ("Penerbitan & Cek Tagihan", "Penagihan dilakukan manual dengan menyusun pesan satu per satu; pelanggan tidak bisa mengecek nominal secara mandiri.", "TRINET-BILL menerbitkan invoice bulanan serentak (bulk billing) dan menyediakan fitur cek mandiri via nomor pelanggan."),
        ("Verifikasi Pembayaran", "Pencocokan bukti transfer dilakukan manual pada rekening bank sehingga pembaruan status lunas memakan waktu 1–2 hari.", "Staf cukup melakukan satu klik konfirmasi pada dasbor billing; sistem langsung mencetak kuitansi PDF dan memperbarui status langganan.")
    ]

    p_col_widths = [Inches(1.5), Inches(2.3), Inches(2.4)]
    for row_idx, data in enumerate(proc_data, start=1):
        row = proc_table.rows[row_idx]
        bg_color = "F8FAFC" if row_idx % 2 == 1 else "FFFFFF"
        for col_idx, text in enumerate(data):
            cell = row.cells[col_idx]
            cell.width = p_col_widths[col_idx]
            set_cell_shading(cell, bg_color)
            set_cell_margins(cell, top=70, bottom=70, left=80, right=80)
            set_cell_border(cell, top={"color":"CBD5E1"}, bottom={"color":"CBD5E1"}, left={"color":"CBD5E1"}, right={"color":"CBD5E1"})
            p = cell.paragraphs[0]
            p.paragraph_format.line_spacing = 1.15
            p.alignment = WD_ALIGN_PARAGRAPH.LEFT
            r = p.add_run(text)
            r.font.name = 'Times New Roman'
            r.font.size = Pt(9.5)

    add_subsection_title("5.4. High-Level Business Requirements")
    add_bullet("HLBR-01", "TRINET-BILL harus menyediakan mekanisme penomoran pelanggan otomatis dengan kode unik (misal: TDM-2601) yang tidak berulang.")
    add_bullet("HLBR-02", "TRINET-BILL harus mampu menghasilkan tagihan bulanan serentak untuk seluruh pelanggan berstatus aktif dengan satu klik instruksi.")
    add_bullet("HLBR-03", "TRINET-BILL harus menyediakan pelacakan status pelanggan berjenjang: Aktif, Pending, dan Terisolir.")
    add_bullet("HLBR-04", "TRINET-BILL harus menyajikan dasbor visual analitik yang menampilkan omzet bulanan, jumlah tagihan pending, dan rasio pelanggan.")
    add_bullet("HLBR-05", "Antarmuka web TRINET-BILL harus dirancang responsif dan ringan untuk diakses dari berbagai peramban seluler maupun komputer meja.")

    add_subsection_title("5.5. Business Rules")
    add_bullet("BR-01 (Siklus Tagihan)", "Invoice diterbitkan pada tanggal 1 setiap bulan kalender dan memiliki batas jatuh tempo pembayaran pada tanggal 20 bulan berjalan.")
    add_bullet("BR-02 (Kebijakan Isolir Layanan)", "Pelanggan yang belum menyelesaikan pembayaran setelah melewati batas tanggal jatuh tempo secara otomatis berubah statusnya menjadi 'Terisolir'.")
    add_bullet("BR-03 (Integritas Alokasi IP)", "Satu alamat IP statis dan port ODP hanya boleh dialokasikan pada tepat satu ID Pelanggan aktif dalam satu waktu.")
    add_bullet("BR-04 (Validasi Mutasi)", "Status tagihan berubah menjadi 'Lunas' hanya setelah staf administrasi/keuangan mengonfirmasi mutasi nominal dana pada rekening bank.")

    # --- 6. Methodology (Page 6) ---
    add_section_title("6. Methodology (Metodologi Pengembangan)")
    add_subsection_title("6.1. Pendekatan Agile / Scrum")
    add_body_paragraph(
        "Metodologi yang diterapkan dalam pengerjaan proyek ini adalah Agile Software Development dengan kerangka kerja Scrum. Pendekatan ini dipilih guna mengakomodasi evaluasi berkala bersama manajemen TRICORE DATA MEDIA dalam setiap siklus Sprint berdurasi 2 mingguan."
    )

    add_subsection_title("6.2. Perangkat dan Lingkungan Pengembangan")
    add_bullet("Bahasa & Framework Backend", "PHP 8.5 dengan Framework Laravel 12 (Arsitektur Model-View-Controller).")
    add_bullet("Frontend & Styling", "Blade Templating Engine, Tailwind CSS v4, Vite 8, dan Alpine.js.")
    add_bullet("Manajemen Basis Data", "Relational Database Management System (RDBMS MySQL / SQLite) dengan skema migration dan seeder data.")
    add_bullet("Alat Kontrol Versi & Kontainer", "Git, GitHub Repository, Docker, dan Nginx Web Server.")

    add_subsection_title("6.3. Tahapan Pengerjaan Proyek")
    add_bullet("Tahap 1 - Analisis Kebutuhan", "Wawancara dengan mitra TRICORE DATA MEDIA, observasi alur kerja ISP, serta penyusunan dokumen spesifikasi BRS dan SRS.")
    add_bullet("Tahap 2 - Perancangan Desain", "Merancang skema relasi basis data (ERD), diagram arsitektur perangkat lunak (UML), serta perancangan antarmuka TRINET-BILL.")
    add_bullet("Tahap 3 - Implementasi (Sprint 1 - 3)", "Mengembangkan modul inti backend dan frontend, manajemen paket, manajemen ODP/IP, mesin billing otomatis, serta portal publik.")
    add_bullet("Tahap 4 - Pengujian Sistem", "Melaksanakan pengujian fungsional berbasis Black-Box Testing dan pengujian penerimaan pengguna (User Acceptance Testing / UAT).")
    add_bullet("Tahap 5 - Penyebaran & Evaluasi", "Konfigurasi server cloud produksi, containerization Docker, migrasi basis data, serta penyusunan laporan akhir.")

    # --- 7. Proposed System Overview (Page 7) ---
    add_section_title("7. Proposed System Overview (Solusi TRINET-BILL)")
    add_body_paragraph(
        "Sistem yang diusulkan mengadopsi arsitektur web modern yang membagi fungsionalitas ke dalam dua ranah utama: Portal Publik dan Portal Administratif Terproteksi:"
    )
    add_bullet("Website Publik TRINET-BILL", "Menyajikan landing page profil perusahaan, katalog paket internet fiber optic lengkap dengan perincian kecepatan, peta interaktif cakupan wilayah (Coverage Area), formulir pendaftaran pelanggan baru online, serta modul pengecekan tagihan mandiri tanpa login.")
    add_bullet("Portal Terproteksi Admin & Mitra TRINET-BILL", "Pusat kendali operasional untuk mengelola data master pelanggan, alokasi ODP dan alamat IP, mesin penerbitan tagihan bulanan otomatis, validasi mutasi pembayaran, serta ekspor kuitansi tagihan digital dalam format PDF.")

    add_body_paragraph(
        "Mekanisme operasional TRINET-BILL beroperasi melalui alur Input - Proses - Output (IPO) berikut:"
    )
    add_bullet("Input Sistem", "Data registrasi pelanggan baru, data master paket langganan, penyesuaian biaya instalasi/diskon, bukti mutasi transfer, dan nomor tiang ODP.")
    add_bullet("Proses Sistem", "Generate nomor pelanggan unik (TDM-XXXX), kalkulasi total tagihan bulanan, enkripsi kata sandi, validasi filter hak akses, dan pembuatan dokumen PDF.")
    add_bullet("Output Sistem", "Faktur tagihan digital (PDF), kuitansi pelunasan resmi, format pesan notifikasi WhatsApp, dan grafik analitik keuangan usaha.")

    # --- 8. Project Deliverables (Page 8) ---
    add_section_title("8. Project Deliverables")
    add_body_paragraph(
        "Artefak dan luaran yang dihasilkan pada akhir pelaksanaan Computing Project TRINET-BILL meliputi:"
    )
    
    del_table = doc.add_table(rows=6, cols=3)
    del_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    d_headers = ["No", "Kategori Luaran", "Deskripsi Artefak yang Dihasilkan"]
    for j, h in enumerate(d_headers):
        cell = del_table.rows[0].cells[j]
        set_cell_shading(cell, "1E3A8A")
        set_cell_margins(cell, top=100, bottom=100, left=100, right=100)
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
            set_cell_margins(cell, top=70, bottom=70, left=80, right=80)
            set_cell_border(cell, top={"color":"CBD5E1"}, bottom={"color":"CBD5E1"}, left={"color":"CBD5E1"}, right={"color":"CBD5E1"})
            p = cell.paragraphs[0]
            p.paragraph_format.line_spacing = 1.15
            p.alignment = WD_ALIGN_PARAGRAPH.CENTER if col_idx == 0 else WD_ALIGN_PARAGRAPH.LEFT
            r = p.add_run(text)
            r.font.name = 'Times New Roman'
            r.font.size = Pt(9.5)

    # --- 9. Project Timeline (Page 8) ---
    add_section_title("9. Project Timeline")
    add_body_paragraph(
        "Rencana pelaksanaan proyek TRINET-BILL dirancang untuk jangka waktu 16 minggu kerja dengan pembagian tahapan dan penanggung jawab sebagai berikut:"
    )

    time_table = doc.add_table(rows=7, cols=7)
    time_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    t_headers = ["No", "Tahapan Kegiatan", "Bln 1", "Bln 2", "Bln 3", "Bln 4", "PIC"]
    for j, h in enumerate(t_headers):
        cell = time_table.rows[0].cells[j]
        set_cell_shading(cell, "1E3A8A")
        set_cell_margins(cell, top=90, bottom=90, left=50, right=50)
        p = cell.paragraphs[0]
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        r = p.add_run(h)
        r.bold = True
        r.font.name = 'Times New Roman'
        r.font.size = Pt(10)
        r.font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)

    timeline_data = [
        ("1", "Analisis Kebutuhan & BRS/SRS TRINET-BILL", "V", "", "", "", "Kanasya"),
        ("2", "Perancangan UI/UX & Basis Data", "", "V", "", "", "Icha & Kanasya"),
        ("3", "Pengembangan Backend & Frontend TRINET-BILL", "", "V", "V", "", "Arsya & Icha"),
        ("4", "Integrasi Billing Engine & WhatsApp Gateway", "", "", "V", "", "Arsya & Afrizal"),
        ("5", "Pengujian Fungsional & UAT TRINET-BILL", "", "", "", "V", "Agnes & Amel"),
        ("6", "Deployment Cloud & Finalisasi Laporan", "", "", "", "V", "Semua Anggota Tim")
    ]

    t_col_widths = [Inches(0.4), Inches(2.7), Inches(0.5), Inches(0.5), Inches(0.5), Inches(0.5), Inches(1.1)]
    for row_idx, data in enumerate(timeline_data, start=1):
        row = time_table.rows[row_idx]
        bg_color = "F8FAFC" if row_idx % 2 == 1 else "FFFFFF"
        for col_idx, text in enumerate(data):
            cell = row.cells[col_idx]
            cell.width = t_col_widths[col_idx]
            set_cell_shading(cell, bg_color)
            set_cell_margins(cell, top=50, bottom=50, left=50, right=50)
            set_cell_border(cell, top={"color":"CBD5E1"}, bottom={"color":"CBD5E1"}, left={"color":"CBD5E1"}, right={"color":"CBD5E1"})
            p = cell.paragraphs[0]
            p.paragraph_format.line_spacing = 1.15
            p.alignment = WD_ALIGN_PARAGRAPH.CENTER if col_idx in [0, 2, 3, 4, 5] else WD_ALIGN_PARAGRAPH.LEFT
            r = p.add_run(text)
            r.font.name = 'Times New Roman'
            r.font.size = Pt(9.5)

    # --- 10. Team Members and Roles (Page 9) ---
    add_section_title("10. Team Members and Roles")
    add_body_paragraph(
        "Struktur organisasi tim pengembang TRINET-BILL dirancang secara terstruktur dengan pembagian peran utama sebagai berikut:"
    )

    team_table = doc.add_table(rows=7, cols=4)
    team_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    r_headers = ["No", "Nama Mahasiswa", "NIM", "Peran / Tanggung Jawab Utama"]
    for j, h in enumerate(r_headers):
        cell = team_table.rows[0].cells[j]
        set_cell_shading(cell, "1E3A8A")
        set_cell_margins(cell, top=100, bottom=100, left=80, right=80)
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
            set_cell_margins(cell, top=70, bottom=70, left=70, right=70)
            set_cell_border(cell, top={"color":"CBD5E1"}, bottom={"color":"CBD5E1"}, left={"color":"CBD5E1"}, right={"color":"CBD5E1"})
            p = cell.paragraphs[0]
            p.paragraph_format.line_spacing = 1.15
            p.alignment = WD_ALIGN_PARAGRAPH.CENTER if col_idx in [0, 2] else WD_ALIGN_PARAGRAPH.LEFT
            r = p.add_run(text)
            r.font.name = 'Times New Roman'
            r.font.size = Pt(9.5)

    # --- 11. References (Page 10) ---
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

    output_path = "Proposal_Computing_Project_Layanan_WiFi_Tricore.docx"
    doc.save(output_path)
    print(f"Proposal successfully rebuilt: {output_path}")

if __name__ == "__main__":
    create_proposal_docx()
