import os
import docx
from docx import Document
from docx.shared import Inches, Pt, RGBColor, Cm
from docx.enum.text import WD_ALIGN_PARAGRAPH, WD_TAB_ALIGNMENT, WD_TAB_LEADER
from docx.enum.table import WD_TABLE_ALIGNMENT
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
    for child in list(sectPr):
        if child.tag.endswith('pgNumType'):
            sectPr.remove(child)
            
    fmt = "romanLower" if is_roman else "decimal"
    pgNumType = parse_xml(f'<w:pgNumType {nsdecls("w")} w:fmt="{fmt}" w:start="{start_num}"/>')
    sectPr.append(pgNumType)

    footer = section.footer
    footer.is_linked_to_previous = False
    p = footer.paragraphs[0]
    p.text = ""
    p.alignment = WD_ALIGN_PARAGRAPH.RIGHT
    
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
    
    if level == 2:
        p.paragraph_format.left_indent = Inches(0.25)
    elif level == 3:
        p.paragraph_format.left_indent = Inches(0.5)
        
    tab_stops = p.paragraph_format.tab_stops
    tab_stop = tab_stops.add_tab_stop(Inches(5.5), WD_TAB_ALIGNMENT.RIGHT, WD_TAB_LEADER.DOTS)

    r_title = p.add_run(title)
    r_title.font.name = 'Times New Roman'
    r_title.font.size = Pt(11)
    r_title.bold = is_bold
    
    r_tab = p.add_run(f"\t{page_str}")
    r_tab.font.name = 'Times New Roman'
    r_tab.font.size = Pt(11)
    r_tab.bold = is_bold
    return p

def create_sdd_document():
    doc = Document()
    
    for section in doc.sections:
        section.page_width = Cm(21.0)
        section.page_height = Cm(29.7)
        section.top_margin = Cm(3.0)
        section.bottom_margin = Cm(3.0)
        section.left_margin = Cm(4.0)
        section.right_margin = Cm(3.0)
        section.different_first_page_header_footer = True
        
    normal_style = doc.styles['Normal']
    normal_style.font.name = 'Times New Roman'
    normal_style.font.size = Pt(12)
    normal_style.font.color.rgb = RGBColor(0x1F, 0x24, 0x2E)
    normal_style.paragraph_format.line_spacing = 1.5
    normal_style.paragraph_format.space_after = Pt(4)
    normal_style.paragraph_format.space_before = Pt(0)

    # 1. COVER PAGE (Section 1)
    sec1 = doc.sections[0]
    sec1.different_first_page_header_footer = True
    sec1.first_page_footer.is_linked_to_previous = False
    
    p_header = doc.add_paragraph()
    p_header.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_header.paragraph_format.line_spacing = 1.15
    p_header.paragraph_format.space_after = Pt(2)
    r1 = p_header.add_run("COMPUTING PROJECT\nSOFTWARE DESIGN DOCUMENT")
    r1.bold = True
    r1.font.size = Pt(14)

    p_title = doc.add_paragraph()
    p_title.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_title.paragraph_format.space_before = Pt(12)
    p_title.paragraph_format.space_after = Pt(14)
    p_title.paragraph_format.line_spacing = 1.15
    r_title = p_title.add_run("TRINET-BILL: TRICORE NETWORK BILLING — SISTEM INFORMASI MANAJEMEN OPERASIONAL DAN PENAGIHAN INTERNET SERVICE PROVIDER BERBASIS WEB PADA TRICORE DATA MEDIA PURWOKERTO")
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

    # 2. FRONT MATTER (Section 2) - Roman: i, ii
    sec2 = doc.add_section(docx.enum.section.WD_SECTION.NEW_PAGE)
    sec2.top_margin = Cm(3.0)
    sec2.bottom_margin = Cm(3.0)
    sec2.left_margin = Cm(4.0)
    sec2.right_margin = Cm(3.0)
    sec2.different_first_page_header_footer = False
    add_clean_footer_pagenum(sec2, is_roman=True, start_num=1)

    # Document Version (Page i)
    p_dv_h = doc.add_paragraph()
    p_dv_h.paragraph_format.space_before = Pt(0)
    p_dv_h.paragraph_format.space_after = Pt(12)
    r_dv_h = p_dv_h.add_run("Document Version")
    r_dv_h.bold = True
    r_dv_h.font.size = Pt(14)
    r_dv_h.font.color.rgb = RGBColor(0x1E, 0x3A, 0x8A)

    dv_table = doc.add_table(rows=4, cols=4)
    dv_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    dv_headers = ["Versi", "Tanggal", "Perubahan", "Penulis"]
    for j, h in enumerate(dv_headers):
        cell = dv_table.rows[0].cells[j]
        set_cell_shading(cell, "1E3A8A")
        set_cell_margins(cell, top=100, bottom=100, left=100, right=100)
        p = cell.paragraphs[0]
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        r = p.add_run(h)
        r.bold = True
        r.font.name = 'Times New Roman'
        r.font.size = Pt(10.5)
        r.font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)

    dv_data = [
        ("v1.0", "15 Maret 2026", "Inisialisasi draf awal arsitektur dan modul SDD", "Kanasya & Arsya"),
        ("v1.1", "28 Maret 2026", "Penambahan skema basis data, ERD, dan DFD Level 1", "Arsya & Afrizal"),
        ("v2.0", "09 Oktober 2026", "Finalisasi desain antarmuka, sequence diagram, dan integrasi TRINET-BILL", "Tim Pengembang TRINET-BILL")
    ]

    dv_widths = [Inches(0.8), Inches(1.3), Inches(2.8), Inches(1.4)]
    for row_idx, data in enumerate(dv_data, start=1):
        row = dv_table.rows[row_idx]
        bg_color = "F8FAFC" if row_idx % 2 == 1 else "FFFFFF"
        for col_idx, text in enumerate(data):
            cell = row.cells[col_idx]
            cell.width = dv_widths[col_idx]
            set_cell_shading(cell, bg_color)
            set_cell_margins(cell, top=70, bottom=70, left=80, right=80)
            set_cell_border(cell, top={"color":"CBD5E1"}, bottom={"color":"CBD5E1"}, left={"color":"CBD5E1"}, right={"color":"CBD5E1"})
            p = cell.paragraphs[0]
            p.paragraph_format.line_spacing = 1.15
            p.alignment = WD_ALIGN_PARAGRAPH.CENTER if col_idx in [0, 1] else WD_ALIGN_PARAGRAPH.LEFT
            r = p.add_run(text)
            r.font.name = 'Times New Roman'
            r.font.size = Pt(10)

    # Table of Content (Page ii)
    doc.add_page_break()

    p_toc_h = doc.add_paragraph()
    p_toc_h.paragraph_format.space_before = Pt(0)
    p_toc_h.paragraph_format.space_after = Pt(14)
    r_toc_h = p_toc_h.add_run("Table of Content")
    r_toc_h.bold = True
    r_toc_h.font.size = Pt(14)
    r_toc_h.font.color.rgb = RGBColor(0x1E, 0x3A, 0x8A)

    add_toc_line(doc, "Document Version", "i", is_bold=True, level=1)
    add_toc_line(doc, "Table of Content", "ii", is_bold=True, level=1)
    add_toc_line(doc, "1. Introduction", "1", is_bold=True, level=1)
    add_toc_line(doc, "1.1. Purpose", "1", is_bold=False, level=2)
    add_toc_line(doc, "1.2. Scope of the System", "1", is_bold=False, level=2)
    add_toc_line(doc, "1.3. References", "2", is_bold=False, level=2)
    add_toc_line(doc, "2. System Architecture Design", "2", is_bold=True, level=1)
    add_toc_line(doc, "2.1. Use Case Diagram", "2", is_bold=False, level=2)
    add_toc_line(doc, "2.2. High-Level Architecture Diagram", "3", is_bold=False, level=2)
    add_toc_line(doc, "2.3. Deployment Architecture", "3", is_bold=False, level=2)
    add_toc_line(doc, "3. Module Design", "4", is_bold=True, level=1)
    add_toc_line(doc, "3.1. Module List", "4", is_bold=False, level=2)
    add_toc_line(doc, "3.2. Module Description (Per Modul)", "4", is_bold=False, level=2)
    add_toc_line(doc, "4. Class Diagram and Object Design", "6", is_bold=True, level=1)
    add_toc_line(doc, "4.1. Class Diagram", "6", is_bold=False, level=2)
    add_toc_line(doc, "4.2. Object Interaction (Sequence Diagram)", "6", is_bold=False, level=2)
    add_toc_line(doc, "5. Database Design", "7", is_bold=True, level=1)
    add_toc_line(doc, "5.1. Entity Relationship Diagram (ERD)", "7", is_bold=False, level=2)
    add_toc_line(doc, "5.2. Database Schema Definitions", "7", is_bold=False, level=2)
    add_toc_line(doc, "6. User Interface Design (UI/UX)", "9", is_bold=True, level=1)
    add_toc_line(doc, "6.1. Wireframes / Mockups & Interface Walkthrough", "9", is_bold=False, level=2)
    add_toc_line(doc, "6.2. Navigation Flow", "10", is_bold=False, level=2)
    add_toc_line(doc, "7. Data Flow and Process Flow", "10", is_bold=True, level=1)
    add_toc_line(doc, "7.1. Data Flow Diagram (DFD)", "10", is_bold=False, level=2)
    add_toc_line(doc, "7.2. Activity Diagram", "11", is_bold=False, level=2)
    add_toc_line(doc, "7.3. State Machine Diagram", "11", is_bold=False, level=2)
    add_toc_line(doc, "8. System Constraints", "12", is_bold=True, level=1)
    add_toc_line(doc, "9. Appendix", "12", is_bold=True, level=1)

    # 3. MAIN BODY (Section 3) - Arabic: 1, 2, 3...
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

    # --- 1. Introduction ---
    add_section_title("1. Introduction")
    add_subsection_title("1.1. Purpose")
    add_body_paragraph(
        "Dokumen Software Design Document (SDD) ini disusun sebagai cetak biru teknis (technical architectural blueprint) untuk perancangan dan konstruksi sistem informasi manajemen operasional dan billing berbasis web: TRINET-BILL (TRIcore Network Billing) pada TRICORE DATA MEDIA Purwokerto. Dokumen ini bertujuan menerjemahkan seluruh kebutuhan fungsional dan non-fungsional dari Software Requirement Specification (SRS) ke dalam arsitektur perangkat lunak yang terinci, mencakup rancangan subsistem, modul perangkat lunak, struktur relasi basis data, interaksi antar objek, antarmuka pengguna, serta batasan teknis lingkungan penyebaran (deployment). Dokumen ini berfungsi sebagai panduan kerja definitif bagi Software Engineer, System Analyst, Database Administrator, dan Tim Quality Assurance selama siklus pengembangan sistem."
    )

    add_subsection_title("1.2. Scope of the System")
    add_body_paragraph(
        "TRINET-BILL (TRIcore Network Billing) adalah platform aplikasi berbasis web monolitik modular terintegrasi yang melayani ekosistem penyedia layanan internet fiber optic (ISP). Sistem ini memiliki cakupan komprehensif sebagai berikut:"
    )
    add_bullet("Fungsi Utama Sistem", "Menyediakan portal publik pemasaran, verifikasi jangkauan kabel fiber optic interaktif (Coverage Area), pendaftaran sambungan baru, modul cek tagihan mandiri tanpa login, dasbor analitik operasional, manajemen siklus hidup data pelanggan (CRUD, status aktif/terisolir), pencatatan titik Optical Distribution Point (ODP) dan alokasi alamat IP, mesin penagihan massal berkala (bulk automated billing engine), validasi mutasi pembayaran, serta integrasi gateway notifikasi WhatsApp.")
    add_bullet("Pengguna Utama (Primary Users)", "Super Administrator (Manajemen Keuangan & Operasional), Mitra/Teknisi Lapangan, serta Pelanggan & Publik (End Users).")
    add_bullet("Manfaat Sistem", "Mengeliminasi pencatatan manual berbasis lembar sebar, memangkas durasi rekonsiliasi pembayaran bulanan hingga 80%, mengamankan integritas data teknis jaringan ODP/IP, dan meningkatkan transparansi layanan kepada masyarakat.")
    add_bullet("Platform & Lingkungan", "Aplikasi web modern yang dibangun di atas kerangka kerja Laravel 12 dan Tailwind CSS v4, dapat diakses responsif melalui peramban web seluler maupun komputer desktop.")

    add_subsection_title("1.3. References")
    add_body_paragraph("Penyusunan dokumen desain perangkat lunak ini berpedoman pada referensi standar berikut:")
    add_bullet("SRS TRINET-BILL", "Dokumen Software Requirement Specification Computing Project TRICORE DATA MEDIA (2026).")
    add_bullet("Standar Desain & Arsitektur", "IEEE Std 1016-2009 (Standard for Information Technology — Systems and Software Engineering — Software Design Descriptions).")
    add_bullet("Standar Web & Keamanan", "OWASP Top 10 Web Application Security Risks (2021) & PSR-12 Coding Style Standard.")
    add_bullet("Regulasi Data", "Undang-Undang Republik Indonesia Nomor 27 Tahun 2022 tentang Pelindungan Data Pribadi (UU PDP).")

    # --- 2. System Architecture Design ---
    add_section_title("2. System Architecture Design")
    add_subsection_title("2.1. Use Case Diagram")
    add_body_paragraph(
        "Sistem TRINET-BILL memfasilitasi interaksi pengguna melalui 3 kelompok aktor utama: Pelanggan/Publik, Mitra/Teknisi, dan Administrator. Hubungan fungsional antarmuka dirangkum dalam tabel pemetaan use case berikut:"
    )

    uc_table = doc.add_table(rows=8, cols=3)
    uc_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    uc_headers = ["Kode Use Case", "Nama Use Case", "Aktor Utama & Deskripsi Fungsional"]
    for j, h in enumerate(uc_headers):
        cell = uc_table.rows[0].cells[j]
        set_cell_shading(cell, "1E3A8A")
        set_cell_margins(cell, top=100, bottom=100, left=100, right=100)
        p = cell.paragraphs[0]
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        r = p.add_run(h)
        r.bold = True
        r.font.name = 'Times New Roman'
        r.font.size = Pt(10.5)
        r.font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)

    uc_data = [
        ("UC-01", "Pengecekan Cakupan & Paket", "Publik: Melihat katalog paket internet, memverifikasi ketersediaan ODP pada peta coverage area."),
        ("UC-02", "Pendaftaran Sambungan Baru", "Publik: Mengisi formulir online, mengirimkan data calon pelanggan, dan memilih paket langganan."),
        ("UC-03", "Cek Tagihan Mandiri", "Pelanggan: Memasukkan Nomor ID Pelanggan (TDM-XXXX) untuk melihat rincian dan riwayat invoice."),
        ("UC-04", "Otentikasi & Manajemen Sesi", "Admin & Mitra: Melakukan login dengan email dan kata sandi terenkripsi, manajemen sesi RBAC."),
        ("UC-05", "Pengelolaan Data Pelanggan & ODP", "Admin & Mitra: Menambah, mengubah, mengisolir, mencatat nomor port ODP dan alamat IP pelanggan."),
        ("UC-06", "Automated Billing & Invoice", "Admin: Menerbitkan tagihan bulanan serentak (bulk generation), mencetak faktur PDF resmi."),
        ("UC-07", "Konfirmasi Pelunasan & Kuitansi", "Admin: Memvalidasi bukti mutasi transfer rekening, mengubah status lunas, menerbitkan kuitansi digital.")
    ]

    uc_widths = [Inches(1.2), Inches(2.3), Inches(2.8)]
    for row_idx, data in enumerate(uc_data, start=1):
        row = uc_table.rows[row_idx]
        bg_color = "F8FAFC" if row_idx % 2 == 1 else "FFFFFF"
        for col_idx, text in enumerate(data):
            cell = row.cells[col_idx]
            cell.width = uc_widths[col_idx]
            set_cell_shading(cell, bg_color)
            set_cell_margins(cell, top=70, bottom=70, left=80, right=80)
            set_cell_border(cell, top={"color":"CBD5E1"}, bottom={"color":"CBD5E1"}, left={"color":"CBD5E1"}, right={"color":"CBD5E1"})
            p = cell.paragraphs[0]
            p.paragraph_format.line_spacing = 1.15
            p.alignment = WD_ALIGN_PARAGRAPH.CENTER if col_idx == 0 else WD_ALIGN_PARAGRAPH.LEFT
            r = p.add_run(text)
            r.font.name = 'Times New Roman'
            r.font.size = Pt(10)

    add_subsection_title("2.2. High-Level Architecture Diagram")
    add_body_paragraph(
        "TRINET-BILL mengimplementasikan arsitektur Model-View-Controller (MVC) berlapis (layered architecture) yang memisahkan ranah presentasi, logika bisnis, dan persistensi data:"
    )
    add_bullet("Presentation Layer (Frontend)", "Dibangun menggunakan Blade Template Engine, Tailwind CSS v4, Vite 8, dan Alpine.js untuk menghadirkan antarmuka reaktif, responsif, dan bernuansa modern (Glassmorphism Dark Theme).")
    add_bullet("Application & Logic Layer (Backend)", "Ditenagai oleh framework Laravel 12 (PHP 8.5) dengan komponen Routing, Form Request Validation, Policy/Gate Authorization, Eloquent ORM, dan DomPDF Service.")
    add_bullet("Data Persistence Layer", "Pangkalan data relasional (MySQL 8.0 / SQLite 3) dengan integritas referensial foreign key berkaskade dan pengindeksan unik pada nomor invoice dan kode pelanggan.")
    add_bullet("External Integration Layer", "Jembatan format notifikasi WhatsApp Business API / Click-to-Chat direct bridge dan Google Maps Geometry API.")
    add_bullet("AI Assistance Layer", "Mesin chatbot cerdas berbasis representasi pengetahuan terstruktur untuk memproses inferensi penanganan kendala teknis (LOS merah, Ping tinggi) dan panduan transaksi.")

    add_subsection_title("2.3. Deployment Architecture")
    add_body_paragraph(
        "Lingkungan produksi TRINET-BILL dirancang dengan pendekatan kontainerisasi (Container-as-a-Service) yang siap dijalankan pada platform Cloud (Render/Koyeb/Railway) dengan topologi arsitektur berikut:"
    )
    add_bullet("Container Engine", "Dockerfile berbasis multi-stage build dengan image dasar PHP 8.5 FPM Alpine dan Nginx High-Performance Web Server.")
    add_bullet("Web Server & Reverse Proxy", "Nginx mengonfigurasi buffering HTTP/2, kompresi Gzip, caching berkas statis (Vite bundle), dan perutean HTTPS/SSL terenkripsi TLS 1.3.")
    add_bullet("Continuous Deployment Pipeline", "Integrasi otomatis via GitHub Webhook pada branch `main`, memicu build skrip asset `npm run build` dan eksekusi migrasi tabel `php artisan migrate --force`.")

    # --- 3. Module Design ---
    add_section_title("3. Module Design")
    add_subsection_title("3.1. Module List")
    add_bullet("MOD-01 (Auth & Session Module)", "Menangani otentikasi akun, proteksi brute-force, dan otorisasi hak akses (Super Admin vs Mitra).")
    add_bullet("MOD-02 (Customer Lifecycle Module)", "Menangani siklus pendaftaran pelanggan, nomor induk otomatis, status koneksi, serta pencatatan ODP & IP.")
    add_bullet("MOD-03 (Automated Billing Module)", "Menangani kalkulasi tarif paket, penerbitan tagihan berkala, konfirmasi bayar, dan ekspor kuitansi PDF.")
    add_bullet("MOD-04 (Package & Tariff Module)", "Menangani konfigurasi master paket internet (kecepatan, harga, kuota unlimited, dan fitur).")
    add_bullet("MOD-05 (Coverage Area & Map Module)", "Menangani pemetaan wilayah fiber optic di Purwokerto Timur, Purwokerto Wetan, dan Sokaraja.")
    add_bullet("MOD-06 (AI Helpdesk & Chatbot Module)", "Menangani simulasi dialog kendala teknis dan pemandu cek tagihan pelanggan.")

    add_subsection_title("3.2. Module Description (Per Modul)")
    
    def render_module_table(mod_data):
        m_table = doc.add_table(rows=len(mod_data), cols=2)
        m_table.alignment = WD_TABLE_ALIGNMENT.CENTER
        for idx, (k, v) in enumerate(mod_data):
            row = m_table.rows[idx]
            c1, c2 = row.cells[0], row.cells[1]
            c1.width = Inches(1.8)
            c2.width = Inches(4.5)
            set_cell_shading(c1, "F1F5F9")
            set_cell_margins(c1, top=60, bottom=60, left=80, right=80)
            set_cell_margins(c2, top=60, bottom=60, left=80, right=80)
            set_cell_border(c1, top={"color":"CBD5E1"}, bottom={"color":"CBD5E1"}, left={"color":"CBD5E1"}, right={"color":"CBD5E1"})
            set_cell_border(c2, top={"color":"CBD5E1"}, bottom={"color":"CBD5E1"}, left={"color":"CBD5E1"}, right={"color":"CBD5E1"})
            p1 = c1.paragraphs[0]
            p1.paragraph_format.line_spacing = 1.15
            r1 = p1.add_run(k)
            r1.bold = True
            r1.font.name = 'Times New Roman'
            r1.font.size = Pt(10)
            p2 = c2.paragraphs[0]
            p2.paragraph_format.line_spacing = 1.15
            r2 = p2.add_run(v)
            r2.font.name = 'Times New Roman'
            r2.font.size = Pt(10)

    add_body_paragraph("Tabel 3.1. Spesifikasi Teknis Modul Pengelolaan Pelanggan (MOD-02):")
    mod_cust_data = [
        ("Nama Modul", "Customer Lifecycle & ODP/IP Management Module"),
        ("Tujuan", "Mengelola master data pelanggan, kode registrasi, status konektivitas, serta pemetaan teknis ODP dan IP."),
        ("Input", "Nama lengkap, nomor telepon, alamat, kecamatan/kelurahan, ID paket, nomor tiang ODP, alokasi IP statis."),
        ("Output", "Record data pelanggan terstruktur, kode pelanggan unik (TDM-XXXX), log status koneksi."),
        ("Dependency", "Package Model, User Model (Partner), Database Migration `customers`."),
        ("Internal Logic", "Sistem memvalidasi keunikan nomor identitas dan IP. Jika lolos validasi, generate nomor urut pelanggan baru dan set status awal `pending`."),
        ("Error Handling", "Penyampaian pesan kesalahan validasi format data (Form Request Exception) dan rollback transaksi basis data jika terjadi gangguan jaringan.")
    ]
    render_module_table(mod_cust_data)

    doc.add_paragraph().paragraph_format.space_after = Pt(6)

    add_body_paragraph("Tabel 3.2. Spesifikasi Teknis Modul Billing Otomatis (MOD-03):")
    mod_bill_data = [
        ("Nama Modul", "Automated Billing & Digital Invoice Engine"),
        ("Tujuan", "Mengotomatisasi penerbitan tagihan bulanan massal, pencatatan transaksi kas masuk, dan pembuatan kuitansi digital."),
        ("Input", "Bulan penagihan (Billing Month), rentang periode aktif, instruksi pelunasan dari staf keuangan."),
        ("Output", "Nomor invoice unik (INV-YYYYMM-XXXX), dokumen PDF faktur tagihan, pesan notifikasi WhatsApp."),
        ("Dependency", "Customer Model, Package Model, DomPDF Library, Invoice Migration."),
        ("Internal Logic", "Menyeleksi seluruh pelanggan dengan status `active`. Memeriksa apakah invoice bulan berjalan sudah terbit. Jika belum, lakukan insert batch ke tabel invoices dengan nominal sesuai harga paket."),
        ("Error Handling", "Pencegahan duplikasi tagihan (Duplicate Invoice Prevention) dan validasi kelengkapan data sebelum cetak PDF.")
    ]
    render_module_table(mod_bill_data)

    # --- 4. Class Diagram and Object Design ---
    add_section_title("4. Class Diagram and Object Design")
    add_subsection_title("4.1. Class Diagram")
    add_body_paragraph(
        "Struktur kelas pada TRINET-BILL mengimplementasikan pola ActiveRecord melalui Laravel Eloquent ORM. Diagram kelas memodelkan entitas utama beserta atribut, metode, dan relasi kardinalitasnya:"
    )
    add_bullet("Class User", "Atribut: `id`, `name`, `email`, `password`, `role (admin/mitra)`, `mitra_name`, `phone`. Metode: `customers()`, `receivedInvoices()`, `isAdmin()`.")
    add_bullet("Class Customer", "Atribut: `id`, `customer_code`, `name`, `phone`, `email`, `address`, `district`, `package_id`, `status`, `odp_code`, `ip_address`. Metode: `package()`, `invoices()`, `partner()`, `isActive()`.")
    add_bullet("Class Package", "Atribut: `id`, `name`, `speed_mbps`, `price`, `description`, `is_featured`. Metode: `customers()`, `invoices()`, `getFormattedPriceAttribute()`.")
    add_bullet("Class Invoice", "Atribut: `id`, `invoice_number`, `customer_id`, `package_id`, `billing_month`, `amount`, `status`, `paid_at`, `payment_method`. Metode: `customer()`, `package()`, `receivedBy()`, `markAsPaid()`.")
    add_bullet("Class CoverageArea", "Atribut: `id`, `district_name`, `subdistricts`, `total_odp`, `status`. Metode: `getAllActiveAreas()`.")

    add_subsection_title("4.2. Object Interaction (Sequence Diagram)")
    add_body_paragraph(
        "Interaksi objek penting dimodelkan dalam skenario alur kerja sekuensial berikut:"
    )
    add_bullet("Alur 1: Autentikasi Pengguna (Login)", "User -> Browser UI (Input Email & Password) -> AuthController -> AuthService (Bcrypt Hash Verification) -> Database Query (users) -> Session Manager (Create Session Token) -> Redirect to Dashboard.")
    add_bullet("Alur 2: Penerbitan Tagihan Massal (Bulk Billing)", "Admin -> Dashboard UI (Klik 'Generate Tagihan Bulan Ini') -> InvoiceController::generateMonthly() -> CustomerModel (Get Active Customers) -> BillingEngine (Iterasi & Buat Invoice Nomor Unik) -> Database Insert Batch (invoices) -> Redirect with Success Toast Alert.")
    add_bullet("Alur 3: Verifikasi Pelunasan & Cetak Kuitansi", "Admin -> Invoice Management UI (Klik 'Konfirmasi Lunas') -> InvoiceController::markPaid() -> InvoiceModel (Update status='paid', paid_at=NOW()) -> CustomerModel (Update status='active') -> PDFEngine (Generate Stream PDF) -> Browser View/Download.")

    # --- 5. Database Design ---
    add_section_title("5. Database Design")
    add_subsection_title("5.1. Entity Relationship Diagram (ERD)")
    add_body_paragraph(
        "Pangkalan data TRINET-BILL dirancang dengan relasi integritas tinggi (3rd Normal Form / 3NF):"
    )
    add_bullet("users (1) ke (N) customers", "Satu user dengan role mitra dapat mendaftarkan banyak customer (Partner Attribution).")
    add_bullet("packages (1) ke (N) customers", "Satu paket internet dapat dipilih oleh banyak customer aktif.")
    add_bullet("customers (1) ke (N) invoices", "Satu customer memiliki banyak invoice tagihan bulanan sepanjang masa berlangganan.")
    add_bullet("packages (1) ke (N) invoices", "Satu invoice merekam snapshot paket langganan yang menjadi dasar penentuan tarif.")
    add_bullet("users (1) ke (N) invoices", "Satu staf admin/keuangan mencatat penerimaan banyak transaksi pembayaran.")

    add_subsection_title("5.2. Database Schema Definitions")
    
    def render_schema_table(fields_data):
        s_table = doc.add_table(rows=len(fields_data)+1, cols=4)
        s_table.alignment = WD_TABLE_ALIGNMENT.CENTER
        headers = ["Field Name", "Data Type", "Null / Key", "Description"]
        for j, h in enumerate(headers):
            cell = s_table.rows[0].cells[j]
            set_cell_shading(cell, "1E3A8A")
            set_cell_margins(cell, top=80, bottom=80, left=60, right=60)
            p = cell.paragraphs[0]
            p.alignment = WD_ALIGN_PARAGRAPH.CENTER
            r = p.add_run(h)
            r.bold = True
            r.font.name = 'Times New Roman'
            r.font.size = Pt(9.5)
            r.font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)

        col_w = [Inches(1.5), Inches(1.2), Inches(1.1), Inches(2.5)]
        for row_idx, data in enumerate(fields_data, start=1):
            row = s_table.rows[row_idx]
            bg_color = "F8FAFC" if row_idx % 2 == 1 else "FFFFFF"
            for col_idx, text in enumerate(data):
                cell = row.cells[col_idx]
                cell.width = col_w[col_idx]
                set_cell_shading(cell, bg_color)
                set_cell_margins(cell, top=60, bottom=60, left=60, right=60)
                set_cell_border(cell, top={"color":"CBD5E1"}, bottom={"color":"CBD5E1"}, left={"color":"CBD5E1"}, right={"color":"CBD5E1"})
                p = cell.paragraphs[0]
                p.paragraph_format.line_spacing = 1.15
                p.alignment = WD_ALIGN_PARAGRAPH.CENTER if col_idx in [1, 2] else WD_ALIGN_PARAGRAPH.LEFT
                r = p.add_run(text)
                r.font.name = 'Times New Roman'
                r.font.size = Pt(9)

    add_body_paragraph("Tabel 5.1. Definisi Skema Tabel Pelanggan (`customers`):")
    cust_fields = [
        ("id", "BIGINT UNSIGNED", "PK, AI, NOT NULL", "Primary Key identitas baris record"),
        ("customer_code", "VARCHAR(50)", "UNIQUE, NOT NULL", "Kode unik pelanggan (misal: TDM-2601)"),
        ("name", "VARCHAR(255)", "NOT NULL", "Nama lengkap pelanggan"),
        ("phone", "VARCHAR(50)", "NOT NULL", "Nomor WhatsApp aktif pelanggan"),
        ("email", "VARCHAR(255)", "NULLABLE", "Alamat surel korespondensi"),
        ("address", "TEXT", "NOT NULL", "Alamat domisili instalasi fisik"),
        ("district", "VARCHAR(100)", "NOT NULL", "Kecamatan (Purwokerto Timur/Wetan/Sokaraja)"),
        ("package_id", "BIGINT UNSIGNED", "FK, NOT NULL", "Relasi ke tabel `packages.id`"),
        ("status", "ENUM", "NOT NULL", "Nilai: 'pending', 'active', 'isolated', 'cancelled'"),
        ("odp_code", "VARCHAR(50)", "NULLABLE", "Kode tiang/kotak ODP fiber optic"),
        ("ip_address", "VARCHAR(50)", "NULLABLE", "Alokasi alamat IP statis/dinamis router"),
        ("created_at", "TIMESTAMP", "NULLABLE", "Waktu pendaftaran record dibuat")
    ]
    render_schema_table(cust_fields)

    doc.add_paragraph().paragraph_format.space_after = Pt(4)

    add_body_paragraph("Tabel 5.2. Definisi Skema Tabel Tagihan (`invoices`):")
    inv_fields = [
        ("id", "BIGINT UNSIGNED", "PK, AI, NOT NULL", "Primary Key unik invoice"),
        ("invoice_number", "VARCHAR(50)", "UNIQUE, NOT NULL", "Nomor faktur (misal: INV-202610-001)"),
        ("customer_id", "BIGINT UNSIGNED", "FK, NOT NULL", "Relasi ke tabel `customers.id`"),
        ("package_id", "BIGINT UNSIGNED", "FK, NOT NULL", "Relasi ke tabel `packages.id`"),
        ("billing_month", "VARCHAR(50)", "NOT NULL", "Bulan tagihan berjalan (misal: Oktober 2026)"),
        ("period_start", "DATE", "NOT NULL", "Tanggal awal periode pemakaian aktif"),
        ("due_date", "DATE", "NOT NULL", "Batas waktu pembayaran (tanggal 20)"),
        ("amount", "DECIMAL(12,2)", "NOT NULL", "Nominal tagihan dalam mata uang Rupiah"),
        ("status", "ENUM", "NOT NULL", "Status tagihan: 'unpaid', 'paid', 'cancelled'"),
        ("paid_at", "TIMESTAMP", "NULLABLE", "Waktu konfirmasi pembayaran diterima"),
        ("payment_method", "VARCHAR(50)", "NULLABLE", "Metode bayar: Transfer BCA / Mandiri / Kas")
    ]
    render_schema_table(inv_fields)

    # --- 6. User Interface Design ---
    add_section_title("6. User Interface Design (UI/UX)")
    add_subsection_title("6.1. Wireframes / Mockups & Interface Walkthrough")
    add_body_paragraph(
        "Antarmuka TRINET-BILL dirancang dengan estetika modern bergaya Glassmorphism Dark Theme menggunakan palet warna dominan Slate 950 (`#090D16`), Cyan Glow (`#06B6D4`), dan Emerald Accent (`#10B981`):"
    )
    add_bullet("1. Landing Page Publik", "Menampilkan Hero Section dinamis dengan badge status latensi real-time, ringkasan profil ISP, grid kartu katalog paket internet, peta interaktif cakupan wilayah, formulir pendaftaran pasang baru, serta formulir cek tagihan mandiri.")
    add_bullet("2. Login Page Portal Mitra", "Menyajikan form otentikasi ringkas dengan proteksi input sandi, fitur 'Ingat Saya', tombol bantuan WhatsApp helpdesk, dan pintasan akun demonstrasi (Admin & Mitra).")
    add_bullet("3. Executive Dashboard Portal", "Menampilkan metrik KPI teratas (Total Pelanggan Aktif, Estimasi Omzet Bulanan, Tagihan Belum Terbayar), grafik fluktuasi finansial, tabel tagihan terbaru, dan tombol aksi cepat.")
    add_bullet("4. Customer Management Page", "Tabel data pelanggan responsif dengan badge status berwarna (Hijau: Aktif, Kuning: Pending, Merah: Terisolir), filter pencarian nama/ID, detail ODP, dan modal form edit.")
    add_bullet("5. Billing & Invoice Report Page", "Dasbor transaksi keuangan dengan tombol bulk generate tagihan, aksi konfirmasi satu klik, tombol direct notifikasi WhatsApp, serta pratinjau cetak faktur digital format PDF.")

    add_subsection_title("6.2. Navigation Flow")
    add_body_paragraph(
        "Alur navigasi antar halaman dipetakan secara terstruktur berdasarkan hak akses pengguna:"
    )
    add_bullet("Jalur Navigasi Publik", "Pengunjung -> Home -> [Cek Paket / Coverage / Cek Tagihan / Registrasi Online] -> Konfirmasi WhatsApp.")
    add_bullet("Jalur Navigasi Portal", "Pengguna Terdaftar -> Login -> Dashboard Utama -> [Kelola Pelanggan | Tagihan & Transaksi | Daftar Paket WiFi] -> Logout.")

    # --- 7. Data Flow and Process Flow ---
    add_section_title("7. Data Flow and Process Flow")
    add_subsection_title("7.1. Data Flow Diagram (DFD)")
    add_body_paragraph(
        "Aliran data dalam sistem TRINET-BILL dimodelkan melalui diagram aliran data bertingkat:"
    )
    add_bullet("DFD Level 0 (Diagram Konteks)", "Menggambarkan entitas eksternal Pelanggan, Mitra, dan Administrator yang bertukar aliran data registrasi, data tagihan, instruksi penerbitan invoice, dan laporan keuangan dengan sistem pusat TRINET-BILL.")
    add_bullet("DFD Level 1", "Menguraikan sistem ke dalam 5 proses inti: (1.0) Manajemen Otentikasi, (2.0) Pemrosesan Registrasi Pelanggan, (3.0) Pengelolaan Data Jaringan ODP/IP, (4.0) Eksekusi Mesin Billing & Invoice, dan (5.0) Rekonsiliasi Pembayaran.")

    add_subsection_title("7.2. Activity Diagram")
    add_body_paragraph(
        "Alur aktivitas operasional memetakan rangkaian aksi yang terjadi pada proses bisnis kritis:"
    )
    add_bullet("Aktivitas Penagihan & Isolir Otomatis", "Sistem memeriksa tanggal server (setiap tanggal 1) -> Menerbitkan invoice unpaid -> Mengirim notifikasi -> Pelanggan melakukan transfer -> Staf mengonfirmasi -> Sistem mengesahkan lunas. Jika hingga tanggal 20 belum lunas -> Sistem otomatis mengubah status pelanggan menjadi 'Terisolir'.")

    add_subsection_title("7.3. State Machine Diagram")
    add_body_paragraph(
        "Dua entitas utama mengalami perubahan status siklus hidup selama sistem beroperasi:"
    )
    add_bullet("Status Pelanggan (Customer State)", "Draft / Pending (Registrasi baru) -> Active (Instalasi fisik selesai & ODP/IP terpasang) -> Isolated / Suspended (Tunggakan melewati tanggal 20) -> Active (Setelah melunasi tagihan) -> Terminated (Permintaan berhenti berlangganan).")
    add_bullet("Status Tagihan (Invoice State)", "Unpaid (Diterbitkan awal bulan) -> Paid (Diverifikasi oleh staf administrasi) / Cancelled (Penyesuaian administratif).")

    # --- 8. System Constraints ---
    add_section_title("8. System Constraints")
    add_body_paragraph(
        "Dalam mengimplementasikan perangkat lunak TRINET-BILL, sejumlah batasan teknis dan regulasi ditetapkan sebagai standar operasional:"
    )
    add_bullet("Hardware Limitations", "Peladen aplikasi minimum membutuhkan 1 vCPU, 1 GB RAM, dan 10 GB SSD Storage untuk melayani hingga 2.000 transaksi pelanggan simultan secara stabil.")
    add_bullet("Software Version Constraints", "Sistem wajib dijalankan pada lingkungan PHP versi 8.2 ke atas (direkomendasikan PHP 8.5) dengan ekstensi pdo_mysql/pdo_sqlite, bcmath, mbstring, dan Node.js 20 LTS.")
    add_bullet("Regulatory Constraints (UU PDP)", "Selaras dengan UU Perlindungan Data Pribadi No. 27 Tahun 2022, kata sandi pengguna wajib dienkripsi menggunakan algoritma Bcrypt (cost factor >= 12), serta nomor identitas dan data kontak pelanggan disimpan dengan hak akses terproteksi.")
    add_bullet("Budget & Time Constraints", "Pengembangan sistem ditargetkan selesai dalam kurun waktu 16 minggu kerja menggunakan teknologi Open Source (FOSS) tanpa biaya lisensi perangkat lunak berbayar tambahan.")

    # --- 9. Appendix ---
    add_section_title("9. Appendix")
    add_body_paragraph("Lampiran matriks perutean web (Routing Endpoints) utama pada sistem TRINET-BILL:")
    
    app_table = doc.add_table(rows=8, cols=4)
    app_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    app_headers = ["HTTP Method", "URI Pattern", "Route Name", "Controller Action & Middleware"]
    for j, h in enumerate(app_headers):
        cell = app_table.rows[0].cells[j]
        set_cell_shading(cell, "1E3A8A")
        set_cell_margins(cell, top=80, bottom=80, left=60, right=60)
        p = cell.paragraphs[0]
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        r = p.add_run(h)
        r.bold = True
        r.font.name = 'Times New Roman'
        r.font.size = Pt(9.5)
        r.font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)

    app_data = [
        ("GET", "/", "home", "CustomerPortalController@index (Public)"),
        ("POST", "/register-customer", "customer.register", "CustomerPortalController@register (Public)"),
        ("POST", "/check-bill", "customer.checkBill", "CustomerPortalController@checkBill (Public)"),
        ("GET", "/login", "login", "AuthController@showLogin (Guest)"),
        ("GET", "/portal/dashboard", "portal.dashboard", "PortalController@dashboard (Auth)"),
        ("POST", "/portal/invoices/generate", "portal.invoices.generate", "InvoiceController@generateMonthly (Auth Admin)"),
        ("POST", "/portal/invoices/{id}/pay", "portal.invoices.pay", "InvoiceController@markPaid (Auth Admin)")
    ]

    app_widths = [Inches(1.0), Inches(1.8), Inches(1.6), Inches(2.0)]
    for row_idx, data in enumerate(app_data, start=1):
        row = app_table.rows[row_idx]
        bg_color = "F8FAFC" if row_idx % 2 == 1 else "FFFFFF"
        for col_idx, text in enumerate(data):
            cell = row.cells[col_idx]
            cell.width = app_widths[col_idx]
            set_cell_shading(cell, bg_color)
            set_cell_margins(cell, top=60, bottom=60, left=60, right=60)
            set_cell_border(cell, top={"color":"CBD5E1"}, bottom={"color":"CBD5E1"}, left={"color":"CBD5E1"}, right={"color":"CBD5E1"})
            p = cell.paragraphs[0]
            p.paragraph_format.line_spacing = 1.15
            p.alignment = WD_ALIGN_PARAGRAPH.CENTER if col_idx == 0 else WD_ALIGN_PARAGRAPH.LEFT
            r = p.add_run(text)
            r.font.name = 'Times New Roman'
            r.font.size = Pt(9)

    # Save to primary and fallback
    output_path = "Software_Design_Document_TRINET-BILL.docx"
    alt_output_path = "Software_Design_Document_TRINET-BILL_Final.docx"
    
    try:
        doc.save(output_path)
        print(f"SDD Document successfully saved to: {output_path}")
    except PermissionError:
        print(f"Note: {output_path} is currently locked/opened in Word. Saving to {alt_output_path} instead.")
        
    doc.save(alt_output_path)
    print(f"SDD Document also saved to: {alt_output_path}")

if __name__ == "__main__":
    create_sdd_document()
