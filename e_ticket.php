<?php include_once 'helpers/helper.php'; ?>
<?php subview('header.php'); ?>
<link rel="preconnect" href="https://fonts.gstatic.com">
<style>
nav {
    display: none !important;
}

@font-face {
  font-family: 'product sans';
  src: url('assets/css/Product Sans Bold.ttf');
}
h2.brand {
    /* font-style: italic; */
    font-size: 27px !important;
}
.vl {
  border-left: 6px solid #424242;
  height: 400px;
}
.text-light2 {
    color: #d9d9d9;
}
h3 {
    /* font-weight: lighter !important; */
    font-size: 21px !important;
    margin-bottom: 20px;  
    font-family: Tahoma, sans-serif;
    font-weight: lighter;
}
p.head {
    text-transform: uppercase;
    font-family: arial;
    font-size: 17px;
    margin-bottom: 10px ;
    color: grey;  
}
p.txt {
    text-transform: uppercase;
    font-family: arial;
    font-size: 25px;
    font-weight: bolder;
}
.bord {
    border: 2px solid lightgray;
    /* border-left: 0px !important; */
}
.out {
    /* box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);   */
    background-color: white;
    padding-left: 25px;
    padding-right: 0px;
    padding-top: 20px;
    border: 2px solid lightgray;
    border-top-left-radius: 25px;
    border-bottom-left-radius: 25px;
}
h2 {
    font-weight: lighter !important;
    font-size: 35px !important;
    margin-bottom: 20px;  
    font-family :'product sans' !important;
    font-weight: bolder;
}
h1 {
    font-weight: lighter !important;
    font-size: 45px !important;
    margin-bottom: 20px;  
    font-family :'product sans' !important;
    font-weight: bolder;
  }

  .terms-section {
        background-color: #f7f7f7;
        border: 1px solid #e0e0e0;
        border-radius: 5px;
        padding: 20px;
    }

    .terms-section ol {
        padding-left: 20px;
    }

    .terms-section ul {
        padding-left: 20px;
    }

    .terms-section li {
        margin-bottom: 10px;
    }

    .terms-section strong {
        color: #333;
    }

    .terms-section p {
        margin-bottom: 10px;
    }
    .terms-heading {
        font-size: 24px;
        font-weight: bold;
        color: #333; /* Change the color as needed */
        text-align: center;
        margin-bottom: 20px;
    }
</style>
<main>
  <?php if(isset($_SESSION['userId'])) {   
    require 'helpers/init_conn_db.php';   ?>     
    <div class="container mb-5"> 
    <!-- <h1 class="text-center text-light mt-4 mb-4">E-TICKETS</h1> -->

      <?php 
    if(isset($_POST['print_but'])) {
        $ticket_id = $_POST['ticket_id'];      
      $stmt = mysqli_stmt_init($conn);
      $sql = 'SELECT * FROM Ticket WHERE ticket_id=?';
      $stmt = mysqli_stmt_init($conn);
      if(!mysqli_stmt_prepare($stmt,$sql)) {
          header('Location: ticket.php?error=sqlerror');
          exit();            
      } else {
          mysqli_stmt_bind_param($stmt,'i',$ticket_id);            
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);
          if ($row = mysqli_fetch_assoc($result)) {   
            $sql_p = 'SELECT * FROM Passenger_profile WHERE passenger_id=?';
            $stmt_p = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt_p,$sql_p)) {
                header('Location: ticket.php?error=sqlerror');
                exit();            
            } else {
                mysqli_stmt_bind_param($stmt_p,'i',$row['passenger_id']);            
                mysqli_stmt_execute($stmt_p);
                $result_p = mysqli_stmt_get_result($stmt_p);
                if($row_p = mysqli_fetch_assoc($result_p)) {
                  $sql_f = 'SELECT * FROM Flight WHERE flight_id=?';
                  $stmt_f = mysqli_stmt_init($conn);
                  if(!mysqli_stmt_prepare($stmt_f,$sql_f)) {
                      header('Location: ticket.php?error=sqlerror');
                      exit();            
                  } else {
                      mysqli_stmt_bind_param($stmt_f,'i',$row['flight_id']);            
                      mysqli_stmt_execute($stmt_f);
                      $result_f = mysqli_stmt_get_result($stmt_f);
                      if($row_f = mysqli_fetch_assoc($result_f)) {
                        $date_time_dep = $row_f['departure'];
                        $date_dep = substr($date_time_dep,0,10);
                        $time_dep = substr($date_time_dep,10,6) ;    
                        $date_time_arr = $row_f['arrivale'];
                        $date_arr = substr($date_time_arr,0,10);
                        $time_arr = substr($date_time_arr,10,6) ; 
                        if($row['class'] === 'E') {
                            $class_txt = 'ECONOMY';
                        } else if($row['class'] === 'B') {
                            $class_txt = 'BUSINESS';
                        }
                        echo '
                        <div class="row mb-5">                                                         
                        <div class="col-9 out">
                            <div class="row ">                                                     
                                <div class="col">
                                    <h2 class="text-secondary mb-0 brand">
                                        E-Ticket</h2> 
                                </div>
                                <div class="col">
                                    <h2 class="mb-0">'.$class_txt.' CLASS</h2>
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-3">  
                                <div class="col-4">
                                    <p class="head">Airline</p>
                                    <p class="txt">'.$row_f['airline'].'</p>
                                </div>            
                                <div class="col-4">
                                    <p class="head">from</p>
                                    <p class="txt">'.$row_f['source'].'</p>
                                </div>
                                <div class="col-4">
                                    <p class="head">to</p>
                                    <p class="txt">'.$row_f['Destination'].'</p>                
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-8">
                                    <p class="head">Passenger</p>
                                    <p class=" h5 text-uppercase">
                                    '.$row_p['f_name'].' '.$row_p['m_name'].' '.$row_p['l_name'].'
                                    </p>                              
                                </div>
                                <div class="col-4">
                                    <p class="head">board time</p>
                                    <p class="txt">12:45</p>
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    <p class="head">departure</p>
                                    <p class="txt mb-1">'.$date_dep.'</p>
                                    <p class="h1 font-weight-bold mb-3">'.$time_dep.'</p>  
                                </div>            
                                <div class="col-3">
                                    <p class="head">arrival</p>
                                    <p class="txt mb-1">'.$date_arr.'</p>
                                    <p class="h1 font-weight-bold mb-3">'.$time_arr.'</p>  
                                </div>
                                <div class="col-3">
                                    <p class="head">gate</p>
                                    <p class="txt">A22</p>
                                </div>            
                                <div class="col-3">
                                    <p class="head">seat</p>
                                    <p class="txt">'.$row['seat_no'].'</p>
                                </div>                
                            </div>                    
                        </div>
                        <div class="col-3 bord pl-0" style="background-color:#376b8d;
                            padding:20px;  border-top-right-radius: 25px; border-bottom-right-radius: 25px;">
                            <div class="row">  
                                <div class="col">                                    
                                <h2 class="text-light text-center brand">
                                    Pacifika Airlines</h2> 
                                </div>                                      
                            </div>                             
                            <div class="row justify-content-center">
                                <div class="col-12">                                    
                                    <img src="assets/images/artic.png" class="mx-auto d-block"
                                    height="180px" width="200px" alt="">
                                </div>                                
                            </div>
                            <div class="row">
                            <h3 class="text-light2 text-center mt-2 mb-0">
                            &nbsp Thank you for choosing us. </br> </br>
                                Please be at the gate at boarding time</h3>    
                            </div>                            
                        </div>                                                 
                        </div>                                               
                      ' ;
                      }
                  }                  
                }
            }                                    
          }
      }   
      
    }   ?> 

    </div>
    
    <div class="row mb-5">
    <div class="col-12">
        <h3 class="text-center mb-4 terms-heading">Terms and Conditions</h3>
        <div class="terms-section">
            <p>Please carefully review the following terms and conditions governing the use of this ticket:</p>
            <ol>
                <li><strong>Booking and Reservation:</strong>
                    <ul>
                        <li>All bookings are subject to availability and acceptance by the airline.</li>
                        <li>Reservation changes, including but not limited to flight dates, times, and destinations, may be permitted subject to applicable fees and restrictions.</li>
                        <li>Passengers are responsible for ensuring the accuracy of their booking details, including names, dates, and contact information.</li>
                        <li>Unconfirmed or unpaid reservations may be canceled without notice.</li>
                    </ul>
                </li>
                <li><strong>Check-In and Boarding:</strong>
                    <ul>
                        <li>Passengers must check-in for their flight within the specified time frame, as indicated by the airline.</li>
                        <li>Boarding gates close strictly at the specified time, and late passengers may be denied boarding.</li>
                        <li>Valid government-issued identification is required for all passengers at check-in and boarding.</li>
                        <li>Passengers must comply with all security procedures and instructions from airline staff during check-in and boarding.</li>
                    </ul>
                </li>
                <li><strong>Baggage:</strong>
                    <ul>
                        <li>Baggage allowances and restrictions vary by airline, fare class, and route. Passengers are advised to check the airline's baggage policy before traveling.</li>
                        <li>Excess baggage fees may apply for luggage exceeding the permitted weight or size limits.</li>
                        <li>Special items, such as sports equipment or musical instruments, may require additional fees and arrangements.</li>
                        <li>The airline is not liable for damage to or loss of unchecked baggage, including carry-on items.</li>
                    </ul>
                </li>
                <li><strong>Flight Changes and Cancellations:</strong>
                    <ul>
                        <li>The airline reserves the right to change or cancel flights due to operational reasons, including but not limited to weather, air traffic control, or aircraft maintenance.</li>
                        <li>In the event of flight cancellations or delays, the airline will make reasonable efforts to rebook passengers on alternative flights or provide compensation as required by law.</li>
                        <li>Passengers may be entitled to refunds or compensation for flight cancellations or delays, subject to applicable regulations and airline policies.</li>
                    </ul>
                </li>
                <li><strong>Refunds and Compensation:</strong>
                    <ul>
                        <li>No refunds will be issued for unused tickets, except as required by law or airline policy.</li>
                        <li>Refunds for canceled flights or unused portions of tickets may be subject to cancellation fees, administrative charges, or fare differences.</li>
                        <li>Compensation for denied boarding, flight cancellations, or delays may be provided in accordance with applicable regulations and airline policies.</li>
                    </ul>
                </li>
                <li><strong>Passenger Conduct and Responsibilities:</strong>
                    <ul>
                        <li>Passengers must comply with all applicable laws, regulations, and airline policies during travel.</li>
                        <li>Disruptive behavior, including but not limited to intoxication, harassment, or violence, will not be tolerated and may result in denial of boarding or legal action.</li>
                        <li>Passengers are responsible for their own health and well-being during travel, including obtaining necessary vaccinations and medical clearances.</li>
                        <li>The airline is not liable for any loss, injury, or damage resulting from passenger misconduct or negligence.</li>
                    </ul>
                </li>
                <li><strong>General Terms:</strong>
                    <ul>
                        <li>These terms and conditions are subject to change without notice. Passengers are advised to review the latest terms and conditions on the airline's website before traveling.</li>
                        <li>These terms and conditions constitute the entire agreement between the passenger and the airline regarding the use of this ticket.</li>
                        <li>Any disputes arising from or related to these terms and conditions shall be governed by the laws of the jurisdiction in which the ticket was purchased.</li>
                    </ul>
                </li>
            </ol>
            <p>By using this ticket, you acknowledge that you have read, understood, and agree to abide by these terms and conditions. If you do not agree with any part of these terms and conditions, you should not use this ticket.</p>
            <p>Please contact the airline or your travel agent if you have any questions or concerns regarding these terms and conditions.</p>
        </div>
    </div>
</div>
  </main>
  <?php } ?>
  <?php subview('footer.php'); ?> 
  <script>
  window.print();
  </script>

