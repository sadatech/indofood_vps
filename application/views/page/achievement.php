<div class="row">
<!-- END SAMPLE TABLE PORTLET-->
<!-- BEGIN SAMPLE TABLE PORTLET-->

<!-- END SAMPLE TABLE PORTLET-->
</div>

<div class="col-lg-4">
  <div class="portlet light portlet-fit bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class=" icon-layers font-green"></i>
            <span class="caption-subject font-green bold uppercase">Sampling BC </span>
        </div>
    </div>
    <div class="portlet-body">
        <div class="mt-element-list">
            <div class="mt-list-head list-simple font-white bg-green-haze">
                <div class="list-head-title-container">
                    <h4 class="list-title"> Report</h4>
                </div>
            </div>
            <div class="mt-list-container list-simple">
                <ul>
                    <li class="mt-list-item">
                        <div class="list-icon-container done">
                            <i class="icon-check"></i>
                        </div>
                        <div class="list-datetime"> <?php echo $sampling->storeBc ?> / <?php echo $sampling->totalToko ?> </div>
                        <div class="list-item-content">
                            <h3 class="uppercase">
                                <a href="javascript:;">Store</a>
                            </h3>
                        </div>
                    </li>
                    <li class="mt-list-item">
                        <div class="list-icon-container done">
                            <i class="icon-check"></i>
                        </div>
                        <div class="list-datetime"> <?php echo ($sampling->samplingBc == null ) ? 0 : $sampling->samplingBc  ?> </div>
                        <div class="list-item-content">
                            <h3 class="uppercase">
                                <a href="javascript:;">Sampling</a>
                            </h3>
                        </div>
                    </li>
                    <li class="mt-list-item">
                        <div class="list-icon-container done">
                            <i class="icon-check"></i>
                        </div>
                        <div class="list-datetime"> <?php echo ( $sampling->strikeSamplingBc == 0 )  ? 0 : $sampling->strikeSamplingBc  ?> </div>
                        <div class="list-item-content">
                            <h3 class="uppercase">
                                <a href="javascript:;">Strike Sampling</a>
                            </h3>
                        </div>
                    </li>
                    <li class="mt-list-item">
                        <div class="list-icon-container done">
                            <i class="icon-check"></i>
                        </div>
                        <div class="list-datetime"> <?php echo (( $sampling->strikeSamplingBTI == 0 )  ? 0 : $sampling->strikeSamplingBTI) / (($sampling->samplingBTI == null ) ? 0 : $sampling->samplingBTI) *100 ?> % </div>
                        <div class="list-item-content">
                            <h3 class="uppercase">
                                <a href="javascript:;">CR Sampling</a>
                            </h3>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
</div>

<div class="col-lg-4">
  <div class="portlet light portlet-fit bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class=" icon-layers font-green"></i>
            <span class="caption-subject font-green bold uppercase">Sampling BTI </span>
        </div>
    </div>
    <div class="portlet-body">
        <div class="mt-element-list">
            <div class="mt-list-head list-simple font-white bg-green-haze">
                <div class="list-head-title-container">
                    <h4 class="list-title"> Report</h4>
                </div>
            </div>
            <div class="mt-list-container list-simple">
                <ul>
                    <li class="mt-list-item">
                        <div class="list-icon-container done">
                            <i class="icon-check"></i>
                        </div>
                        <div class="list-datetime"> <?php echo $sampling->storeBTI ?> / <?php echo $sampling->totalToko ?> </div>
                        <div class="list-item-content">
                            <h3 class="uppercase">
                                <a href="javascript:;">Store</a>
                            </h3>
                        </div>
                    </li>
                    <li class="mt-list-item">
                        <div class="list-icon-container done">
                            <i class="icon-check"></i>
                        </div>
                        <div class="list-datetime"> <?php echo ($sampling->samplingBTI == null ) ? 0 : $sampling->samplingBTI  ?> </div>
                        <div class="list-item-content">
                            <h3 class="uppercase">
                                <a href="javascript:;">Sampling</a>
                            </h3>
                        </div>
                    </li>
                    <li class="mt-list-item">
                        <div class="list-icon-container done">
                            <i class="icon-check"></i>
                        </div>
                        <div class="list-datetime"> <?php echo ( $sampling->strikeSamplingBTI == 0 )  ? 0 : $sampling->strikeSamplingBTI  ?> </div>
                        <div class="list-item-content">
                            <h3 class="uppercase">
                                <a href="javascript:;">Strike Sampling</a>
                            </h3>
                        </div>
                    </li>
                    <li class="mt-list-item">
                        <div class="list-icon-container done">
                            <i class="icon-check"></i>
                        </div>
                        <div class="list-datetime"> <?php echo (( $sampling->strikeSamplingBTI == 0 )  ? 0 : $sampling->strikeSamplingBTI) / (($sampling->samplingBTI == null ) ? 0 : $sampling->samplingBTI) *100 ?> % </div>
                        <div class="list-item-content">
                            <h3 class="uppercase">
                                <a href="javascript:;">CR Sampling</a>
                            </h3>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Rusk -->
<div class="col-lg-4">
  <div class="portlet light portlet-fit bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class=" icon-layers font-green"></i>
            <span class="caption-subject font-green bold uppercase">Sampling Rusk </span>
        </div>
    </div>
    <div class="portlet-body">
        <div class="mt-element-list">
            <div class="mt-list-head list-simple font-white bg-green-haze">
                <div class="list-head-title-container">
                    <h4 class="list-title"> Report</h4>
                </div>
            </div>
            <div class="mt-list-container list-simple">
                <ul>
                    <li class="mt-list-item">
                        <div class="list-icon-container done">
                            <i class="icon-check"></i>
                        </div>
                        <div class="list-datetime"> <?php echo $sampling->storeRusk ?> / <?php echo $sampling->totalToko ?> </div>
                        <div class="list-item-content">
                            <h3 class="uppercase">
                                <a href="javascript:;">Store</a>
                            </h3>
                        </div>
                    </li>
                    <li class="mt-list-item">
                        <div class="list-icon-container done">
                            <i class="icon-check"></i>
                        </div>
                        <div class="list-datetime"> <?php echo ($sampling->samplingRusk == null) ? 0: $sampling->samplingRusk ?> </div>
                        <div class="list-item-content">
                            <h3 class="uppercase">
                                <a href="javascript:;">Sampling</a>
                            </h3>
                        </div>
                    </li>
                    <li class="mt-list-item">
                        <div class="list-icon-container done">
                            <i class="icon-check"></i>
                        </div>
                        <div class="list-datetime"> <?php echo ($sampling->strikeSamplingRusk == null) ? 0: $sampling->strikeSamplingRusk ?> </div>
                        <div class="list-item-content">
                            <h3 class="uppercase">
                                <a href="javascript:;">Strike Sampling</a>
                            </h3>
                        </div>
                    </li>
                    <li class="mt-list-item">
                        <div class="list-icon-container done">
                            <i class="icon-check"></i>
                        </div>
                        <div class="list-datetime"> <?php echo (($sampling->strikeSamplingRusk == null) ? 0: $sampling->strikeSamplingRusk )/ (($sampling->samplingRusk == null) ? 0: $sampling->samplingRusk) *100 ?> % </div>
                        <div class="list-item-content">
                            <h3 class="uppercase">
                                <a href="javascript:;">CR Sampling</a>
                            </h3>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Pudding -->
<div class="col-lg-4">
  <div class="portlet light portlet-fit bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class=" icon-layers font-green"></i>
            <span class="caption-subject font-green bold uppercase">Sampling Pudding </span>
        </div>
    </div>
    <div class="portlet-body">
        <div class="mt-element-list">
            <div class="mt-list-head list-simple font-white bg-green-haze">
                <div class="list-head-title-container">
                    <h4 class="list-title"> Report</h4>
                </div>
            </div>
            <div class="mt-list-container list-simple">
                <ul>
                    <li class="mt-list-item">
                        <div class="list-icon-container done">
                            <i class="icon-check"></i>
                        </div>
                        <div class="list-datetime"> <?php echo $sampling->storePudding ?> / <?php echo $sampling->totalToko ?> </div>
                        <div class="list-item-content">
                            <h3 class="uppercase">
                                <a href="javascript:;">Store</a>
                            </h3>
                        </div>
                    </li>
                    <li class="mt-list-item">
                        <div class="list-icon-container done">
                            <i class="icon-check"></i>
                        </div>
                        <div class="list-datetime"> <?php echo ($sampling->samplingPudding == null ) ? 0 : $sampling->samplingPudding ?> </div>
                        <div class="list-item-content">
                            <h3 class="uppercase">
                                <a href="javascript:;">Sampling</a>
                            </h3>
                        </div>
                    </li>
                    <li class="mt-list-item">
                        <div class="list-icon-container done">
                            <i class="icon-check"></i>
                        </div>
                        <div class="list-datetime"> <?php echo ($sampling->strikeSamplingPudding == null ) ? 0 : $sampling->strikeSamplingPudding ?> </div>
                        <div class="list-item-content">
                            <h3 class="uppercase">
                                <a href="javascript:;">Strike Sampling</a>
                            </h3>
                        </div>
                    </li>
                    <li class="mt-list-item">
                        <div class="list-icon-container done">
                            <i class="icon-check"></i>
                        </div>
                        <div class="list-datetime"> <?php echo (($sampling->strikeSamplingPudding == null ) ? 0 : $sampling->strikeSamplingPudding) / (($sampling->samplingPudding == null ) ? 0 : $sampling->samplingPudding) *100 ?> % </div>
                        <div class="list-item-content">
                            <h3 class="uppercase">
                                <a href="javascript:;">CR Sampling</a>
                            </h3>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Others -->
<div class="col-lg-4">
  <div class="portlet light portlet-fit bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class=" icon-layers font-green"></i>
            <span class="caption-subject font-green bold uppercase">Sampling Others </span>
        </div>
    </div>
    <div class="portlet-body">
        <div class="mt-element-list">
            <div class="mt-list-head list-simple font-white bg-green-haze">
                <div class="list-head-title-container">
                    <h4 class="list-title"> Report</h4>
                </div>
            </div>
            <div class="mt-list-container list-simple">
                <ul>
                    <li class="mt-list-item">
                        <div class="list-icon-container done">
                            <i class="icon-check"></i>
                        </div>
                        <div class="list-datetime"> <?php echo $sampling->storeOthers ?> / <?php echo $sampling->totalToko ?> </div>
                        <div class="list-item-content">
                            <h3 class="uppercase">
                                <a href="javascript:;">Store</a>
                            </h3>
                        </div>
                    </li>
                    <li class="mt-list-item">
                        <div class="list-icon-container done">
                            <i class="icon-check"></i>
                        </div>
                        <div class="list-datetime"> <?php echo ($sampling->samplingOthers == null) ? 0 : $sampling->samplingOthers ?> </div>
                        <div class="list-item-content">
                            <h3 class="uppercase">
                                <a href="javascript:;">Sampling</a>
                            </h3>
                        </div>
                    </li>
                    <li class="mt-list-item">
                        <div class="list-icon-container done">
                            <i class="icon-check"></i>
                        </div>
                        <div class="list-datetime"> <?php echo ($sampling->strikeSamplingOthers == null) ? 0 : $sampling->strikeSamplingOthers ?> </div>
                        <div class="list-item-content">
                            <h3 class="uppercase">
                                <a href="javascript:;">Strike Sampling</a>
                            </h3>
                        </div>
                    </li>
                    <li class="mt-list-item">
                        <div class="list-icon-container done">
                            <i class="icon-check"></i>
                        </div>
                        <div class="list-datetime"> <?php echo (($sampling->strikeSamplingOthers == null) ? 0 : $sampling->strikeSamplingOthers) / (($sampling->samplingOthers == null) ? 0 : $sampling->samplingOthers) *100 ?> % </div>
                        <div class="list-item-content">
                            <h3 class="uppercase">
                                <a href="javascript:;">CR Sampling</a>
                            </h3>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
</div>
</div>
