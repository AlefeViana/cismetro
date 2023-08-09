<div class="cab">
            <div style="display: flex; width: 100%;font-weight: bolder; font-family: 'Arial';">
                <div style="margin-left: 0; margin-right: 0;width: 15%">
                    <div>
                        <img style="align-self: flex-start; width: 100%" src="img/logo_cismetro.png">
                    </div>
                </div>
                <div style="margin-left: 0; margin-right: 0;width: 70%">
                    <div>
                        <p style="font-size: 11px; text-align: center; font-weight: bold;">
                            <?php echo $lin['nmconsorcio'] ?? "..." ?>
                        </p>
                        <p style="font-size: 11px; text-align: center; font-weight: bold;">
                            <?php echo $lin['enderecoconsorcio'] ?? "..." ?>
                        </p>
                        <p style="font-size: 11px; text-align: center; font-weight: bold;">
                            <?php echo $lin['dadosconsorcio'] ?? "..." ?>
                        </p>
                    </div>
                </div>
                <div style="border: 1px solid;width: 15%;height:80px">
                    <p style="font-size: 11px; text-align: center; font-weight: bold;">DATA EMISSÃO
                        <?php echo date('d/m/Y'); ?>
                    </p>
                    <p style="font-size: 11px; text-align: center; font-weight: bold;">HORA EMISSÃO
                        <?php echo date('H:i:s'); ?>
                    </p>
                </div>
            </div>
        </div>