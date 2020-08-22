@extends('frontend.master')
@section('content')
  <!-- catg header banner section -->
  <section id="aa-catg-head-banner">
   <img src="{!! url('public/images/cartpannel.jpg') !!}" alt="fashion img" style="width: 1920px; height: 450px;" >
   <div class="aa-catg-head-banner-area">
     <div class="container">
      <div class="aa-catg-head-banner-content">
        <h2>Giỏ hàng</h2>
        <ol class="breadcrumb">
          <li><a href="{!! url('/') !!}">Trang chủ</a></li>         
          <li class="active">Giỏ hàng</li>
        </ol>
      </div>
     </div>
   </div>
  </section>
  <!-- / product category -->

  <!-- Support section -->
  @include('frontend.blocks.trans')
  <!-- / Support section -->

 <!-- Cart view section -->
 <section id="cart-view">
   <div class="container">
     <div class="row">
       <div class="col-md-12">
         <div class="cart-view-area">
           <div class="cart-view-table">
             <form action="">
               <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th></th>
                        <th></th>
                        <th>Ảnh</th>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                      </tr>
                    </thead>
                    
                    <tbody>
                    <form action="" method="POST" accept-charset="utf-8">
                    <input type="hidden" name="_token" value="{!! csrf_token() !!}" />
                    @foreach ($content as $item)
                      <?php 
                        $sanpham = DB::table('sanpham')->where('id',$item->id)->first();
                        $total += $item->price*$item->qty;
                      ?>
                      <tr>
                        <td><button style="background-color:unset" product-id="{{$sanpham->id}}" class="btn updatecart edit" id="{!! $item->rowid !!}"><fa class="fa fa-edit"></fa></button></td>
                        <td><a class="remove" href='{!! URL::route("xoasanphamgiohang", ["id" => $item->id] ) !!}'><fa class="fa fa-close"></fa></a></td>
                        <td><a href="{!! url('san-pham',$sanpham->sanpham_url) !!}"><img src="{!! asset('resources/upload/sanpham/'.$sanpham->sanpham_anh) !!}"  style="width: 45px; height: 50px;"></a></td>
                        <td><a href="{!! url('san-pham',$sanpham->sanpham_url) !!}">{!! $sanpham->sanpham_ten !!}</a></td>
                        <!-- <td><a class="aa-cart-title" href="{!! url('san-pham',$sanpham->sanpham_url) !!}">{!!  $item->name !!}</a></td> -->
                        <td>{!! number_format("$item->price",0,",",".") !!}vnđ</td>
                        <td><input id="id-{{$sanpham->id}}" currentQuantity="{!!$item->qty!!}" hrefUrl="{!! url('mua-nhieu-hang') !!}" data-id="{{$sanpham->id}}" min=1 class="qty aa-cart-quantity input_order_quantity" name='order_quantity' type="number" value="{!!  $item->qty !!}">
                        <div class="message_order_quantity" style="color: #e40202"></div></td>
                        <td>{!! number_format($item->price*$item->qty,0,",",".") !!}vnđ</td>
                      </tr>
                    @endforeach
                    </form>
                      </tbody>
                      
                  </table>
                </div>
             </form>
             <!-- Cart Total view -->
             <div class="cart-view-total">
               <!-- <h4>Tổng tiền</h4> -->
               <table class="aa-totals-table">
                 <tbody>
                   <tr>
                     <th>Tổng tiền</th>
                     <td> {!! number_format("$total",0,",",".") !!}vnđ</td>
                   </tr>
                 </tbody>
               </table>
               @if (Auth::check())
                  <a href="{!! url('/') !!}" class="aa-cart-view-btn"> Mua tiếp</a>
                  <a href="{!! URL::route('getThanhtoan') !!}" class="aa-cart-view-btn">Thanh Toán</a>
                  
               @else
                  <a href="{!! url('/') !!}" class="aa-cart-view-btn">Mua tiếp</a>
                  <a href="{!! url('login') !!}" class="aa-cart-view-btn">Thanh Toán</a>
               @endif
               
             </div>
           </div>
         </div>
       </div>
     </div>
   </div>
 </section>

 <!-- / Cart view section -->
 <!-- Footer -->
@include('frontend.blocks.footer')
<!-- / Footer -->
@stop

@section('javascript')
<script>
  $(document).ready(function(){
    // When change quantity
    $('input[name="order_quantity"]').change(function(){
      var quantity = $(this).val();
      var maxQuantity = $(this).attr("max-quantity");
      
      if (quantity < 1) {
        $('.message_order_quantity').text('Số lượng không hợp lệ!');
      } else if (quantity > maxQuantity) {
        $('.message_order_quantity').text('Vượt quá số lượng trong kho!');
      } else {
        $('.message_order_quantity').text('');
      }
    });

    // Event when click on button buy product
    $('.updatecart').click(function(e){
      e.preventDefault();
      e.stopPropagation();
      // var quantity = $('.input_order_quantity').val();
      // var max = $('input[name="order_quantity"]').attr('max');

      // if (quantity < 1 || max < quantity) {
      //   return;
      // }
      
      // $.post($(this).attr('href'),
      // {
      //   id: $(this).attr('data-id'),
      //     quantity: quantity
      // },
      // function(data, status){
      //   alert("Data: " + data + "\nStatus: " + status);
      // });
      let inputEle = $(`#id-${$(this).attr('product-id')}`);
      let id = $(this).attr('product-id');
      let quantity = inputEle.val();
      
      let currentQuantity = inputEle.attr('currentQuantity');
      let newQuantity = quantity - currentQuantity;
      if (newQuantity == 0) {
        newQuantity = 1;
      }
      let url = 'http://localhost/thucphamsach/mua-nhieu-hang/'+inputEle.attr('data-id')+'/'+newQuantity
      console.log(url, quantity, currentQuantity, newQuantity);
      $.ajax({
        url: url,
        method: 'get',
        dataType: 'JSON',
        success: function(response){
          // var total = 0;

          // for (var id in response) {
          //   total += response[id].qty;
          // }

          // $('.aa-cart-link .aa-cart-notify').text(total);
          // alert('Đã thêm vào giỏ hàng!');
        },
        error: function(){}
      });
      setTimeout(() => {
        location.reload();
      }, 500);
      
    });
  });
</script>
@endsection