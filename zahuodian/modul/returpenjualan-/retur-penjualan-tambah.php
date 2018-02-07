  <table class='table table-hover' border=0 id='tambah'>
  <tr>
        <td align="left">No Retur</td><td><strong>:</strong></td>
        <td><?php $tampil=mysql_query("SELECT kode_rjb FROM `trans_retur_penjualan` order by id desc limit 1 ");
            $kode    = mysql_fetch_array($tampil);
            echo kodesurat($kode[kode_rjb],'RJB','koderjb','koderjb'); ?></td>
        <td align="left">No Invoice</td><td><strong>:</strong></td>
        <td><input id='no_invoice' class='form-control' name='no_invoice' data-toggle='modal' data-target='#modalrjb' readonly ></td>
  </tr>
  <tr>
        <td align="left">Customer</td> <td><strong>:</strong></td><td id='sup'>
        <input type='hidden' class='form-control' id='customer' name='customer' readonly/>
        <input type='text' class='form-control' id='customer2' readonly/></td>
        <td align="left">Jenis Retur</td><td><strong>:</strong></td> <td><select class="form-control" name=jenis_retur id=jenis_retur read >
         <option value="2">Potong Nota</option>
         </select></td>
  </tr>
  <tr>
    <td align="left">Alasan Retur</td><td><strong>:</strong></td>
    <td><textarea class="form-control" id="ket" name="ket" required></textarea></td>
    <td align="left">Tgl. Retur</td><td><strong>:</strong></td>
    <td><input class="form-control datetimepicker" value="<?php date('Y-m-d') ?>"id="tgl_rjb" name="tgl_rjb" required></td>
  </tr>
</table>

<table id="tblrjb" class="table table-hover table-bordered" cellspacing="0">
</table>



<div class="modal fade" id="modalrjb" role="dialog">
    <div class="modal-dialog modal-lg">    
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Nomer Invoice</h4>
        </div><!-- ############## end Modal header -->
        <div class="modal-body">
          <table id="modalnoinvoice" border="1" class="table table-hover" style="width: 100%;">
              <thead style="background-color:#F5F5F5;">
                  <tr >
                    <th id="tablenumber">No</th>
                    <th>Customer</th>
                    <th>No Invoice</th>
                    <th>Tanggal</th>
                    <th>Grand Total</th>
                    <th>Total Pembayaran</th>
                    <th>Aksi</th>
                  </tr>
              </thead>
              <tbody id="tampilnota">
              </tbody>
          </table>
        </div> 
      </div>   
    </div> 
  </div>
