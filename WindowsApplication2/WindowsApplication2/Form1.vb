Public Class Form_Pencarian_Barang
    Dim a As String
    Private Sub textPencarian_TextChanged(sender As Object, e As EventArgs) Handles textPencarian.TextChanged
        tablePencarian.DataSource = dbSelect("SELECT  `Barcode` as barcode, `nama_barang` as `Nama Barang`, `harga_1` as `Harga 1`, `harga_2` as `Harga 2`, `harga_3` as `Harga 3`, `harga_4` as `Harga 4`, `harga_5` as `Harga 5`, `total_barang` as `Total Stok` FROM `view_barang` where instr(`kode_barang_supplier`,'" & textPencarian.Text & "') > 0 or instr(`kode_barang_merk`,'" & textPencarian.Text & "') > 0 or instr(`kode_barang_kategori`,'" & textPencarian.Text & "') > 0 or instr(`kode_barang`,'" & textPencarian.Text & "') > 0 or instr(`barcode`,'" & textPencarian.Text & "') > 0 or instr(`nama_barang`,'" & textPencarian.Text & "') > 0 limit 32")
    End Sub

    Private Sub Form1_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        koneksi()
        tablePencarian.DataSource = dbSelect("SELECT  `Barcode` as barcode, `nama_barang` as `Nama Barang`, `harga_1` as `Harga 1`, `harga_2` as `Harga 2`, `harga_3` as `Harga 3`, `harga_4` as `Harga 4`, `harga_5` as `Harga 5`, `total_barang` as `Total Stok` FROM `view_barang`  limit 32")
        tablePencarian.Columns(0).Width = 70
        tablePencarian.Columns(1).Width = 400
        tablePencarian.Columns(1).DefaultCellStyle.Alignment = DataGridViewContentAlignment.MiddleLeft
        tablePencarian.Columns(2).DefaultCellStyle.Alignment = DataGridViewContentAlignment.MiddleRight
        tablePencarian.Columns(3).DefaultCellStyle.Alignment = DataGridViewContentAlignment.MiddleRight
        tablePencarian.Columns(4).DefaultCellStyle.Alignment = DataGridViewContentAlignment.MiddleRight
        tablePencarian.Columns(5).DefaultCellStyle.Alignment = DataGridViewContentAlignment.MiddleRight
        tablePencarian.Columns(6).DefaultCellStyle.Alignment = DataGridViewContentAlignment.MiddleRight
        tablePencarian.Columns(7).DefaultCellStyle.Alignment = DataGridViewContentAlignment.MiddleCenter






    End Sub
End Class
