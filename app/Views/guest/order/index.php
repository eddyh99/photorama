<div class="h-100 w-100" id="content-bg">
    <div class="d-flex justify-content-center align-items-center h-100">
        <div class="container text-black">
            <div class="row">
                <div class="col">
                    <!-- 1 of 3 -->
                </div>
                <div class="col-6">
                    <div class="card" style="background-color: white;">
                        <div class="card-body text-center">
                            <h2 class="card-title text-uppercase fw-bold">How Many Prints <br>Do You Wants?</h2>
                            <img class="d-block mx-auto" src="<?= BASE_URL ?>assets/img/img-order.png" alt="Order">
                            <div>
                                <div class="d-flex justify-content-center align-items-center mb-3">
                                    <!-- Button kurang -->
                                    <button class="btn btn-lg btn-primary me-5 rounded-circle" id="decrease"><i class="bx bx-minus"></i></button>
                                    <span class="fw-bold fs-2" id="count">1</span>
                                    <!-- Button tambah -->
                                    <button class="btn btn-lg btn-primary ms-5 rounded-circle" id="increase"><i class="bx bx-plus"></i></button>
                                </div>
                            </div>
                            <h2>IDR <span id="price">17.500</span></h2>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="d-flex flex-column justify-content-center align-items-center h-100">
                        <div class="text-center">
                            <label class="form-label text-uppercase text-black fs-3" for="upload foto">Voucher Code</label>
                            <div class="input-group input-group-merge mb-2">
                                <input
                                    type="text"
                                    class="form-control text-center"
                                    id="voc"
                                    name="voc" placeholder="(opsional)"/>
                            </div>
                        </div>
                        <button id="next" class="btn btn-danger text-white fs-5 mt-3 text-center">NEXT</button>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>