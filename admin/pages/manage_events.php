<?php
// ============================================
// 1. AUTH MIDDLEWARE (Security, DB, Session Vars)
// ============================================
require_once '../middleware/authGuard.php';

// ============================================
// 2. CONTROLLER LOGIC (Manage Events Logic)
// ============================================
require_once '../controllers/ManageEventController.php';

include '../layout/header.php'; // Or header
?>


    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark"><i class="fas fa-bullhorn text-warning me-2"></i> EDL Socials & CSR Updates</h3>
        <button class="btn btn-warning shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#eventModal">
            <i class="fas fa-plus me-1"></i> Add Announcement
        </button>
    </div>

    <?php if($msg) echo "<div class='alert alert-success border-0 shadow-sm'>$msg</div>"; ?>
    <?php if($err) echo "<div class='alert alert-danger border-0 shadow-sm'>$err</div>"; ?>

    <!-- Display Active Events Table -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark"><tr><th width="18%">Hero Graphic</th><th>News Detail Feed</th><th width="15%" class="text-end">Controls</th></tr></thead>
                <tbody>
                    <?php 
                    $res = $conn->query("SELECT * FROM company_events ORDER BY id DESC");
                    if($res && $res->num_rows > 0) { while($r = $res->fetch_assoc()) { 
                        // Assigning custom tag styles logically
                        $bc = ($r['category']=='Greeting') ? 'bg-success' : (($r['category']=='CSR')?'bg-primary':'bg-danger');
                    ?>
                    <tr>
                        <td>
                            <img src="<?php echo str_replace('../../uploads/', '../uploads/', $r['image_path']); ?>" 
                                 style="width: 130px; height: 75px; object-fit: cover; border-radius: 6px; box-shadow:0 3px 5px rgba(0,0,0,0.1)">
                        </td>
                        <td class="pt-3">
                            <span class="badge <?php echo $bc; ?> rounded-pill mb-1"><?php echo $r['category']; ?></span><br>
                            <b class="text-dark fs-6" style="display:inline-block; margin-bottom:4px;"><?php echo htmlspecialchars($r['title']); ?></b><br>
                            <span class="small text-dark d-block pb-1" style="max-width: 480px;"><?php echo mb_strimwidth($r['message'], 0, 95, "..."); ?></span>
                            <small class="text-secondary opacity-75" style="font-size:11px;">Posted by: <?php echo $r['posted_by']; ?> &nbsp;|&nbsp; On <?php echo date('M d, Y, h:ia', strtotime($r['created_at'])); ?></small>
                        </td>
                        <td class="text-end align-top pt-4">
                            <?php if ($_SESSION['role'] == 'Super Admin'): ?>
                                <a href="?del=<?php echo $r['id']; ?>" class="btn btn-sm btn-outline-danger me-2" onclick="return confirm('You are permanently removing this notice from the front-page. Confirm?');"><i class="fas fa-trash"></i> Drop Post</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php }} else { echo "<tr><td colspan='3' class='text-center py-5 text-muted'>There are no actively published updates currently on board.</td></tr>"; } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- EVENT CREATION MODAL WITH IMAGE DROP FIELD -->
<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header bg-dark text-white border-0"><h5 class="modal-title fw-bold"><i class="fas fa-edit text-warning"></i> Add Company Note / CSR</h5></div>
                <div class="modal-body p-4 bg-light">
                    
                    <!-- Compression Note Banner -->
                    <div class="alert alert-info py-2" style="font-size:0.75rem;"><i class="fas fa-compress-arrows-alt me-1"></i> Added images will be dynamically compressed by AI Client Algorithm ensuring quick server uploads safely.</div>
                    
                    <div class="mb-3">
                        <label class="small fw-bold text-dark text-uppercase mb-1">Theme Post Heading</label>
                        <input type="text" name="e_title" class="form-control" required placeholder="E.g: Wesak Blessings!">
                    </div>
                    
                    <div class="mb-3">
                        <label class="small fw-bold text-dark text-uppercase mb-1">Banner Type Focus</label>
                        <select name="e_cat" class="form-select border border-secondary shadow-sm">
                            <option value="Greeting">Holiday Seasonal Greeting</option>
                            <option value="CSR">Local Community Welfare (CSR) Drive</option>
                            <option value="Alert">Operational Disruption & Public Notices Alert</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="small fw-bold text-dark text-uppercase mb-1">Add Feature Snapshot Element Image</label>
                        <!-- ADDED `id="eventImgInput"` TO ENGAGE THE SCRIPTING LAYER -->
                        <input type="file" id="eventImgInput" name="e_image" accept="image/png, image/jpeg" class="form-control border-secondary shadow-sm bg-white" required>
                    </div>
                    
                    <div class="mb-2">
                        <label class="small fw-bold text-dark text-uppercase mb-1">Story Summary Lines Overview</label>
                        <textarea name="e_msg" class="form-control border-secondary shadow-sm bg-white" rows="3" required></textarea>
                    </div>
                    
                </div>
                <!-- Dynamic Processing Status ID applied properly -->
                <div class="modal-footer border-0 p-0 m-0"><button type="submit" name="add_event" id="publishBtnMain" class="btn btn-warning w-100 rounded-0 p-3 fs-6 fw-bold">Post Event Banner Securely</button></div>
            </form>
        </div>
    </div>
</div>

<!-- ======================= EXTERNAL JAVASCRIPT JS UTILITIES (COMPRESSION CDN REQUIRED ON LOAD) ======================================== -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/browser-image-compression@2.0.2/dist/browser-image-compression.js"></script>

<script>
    // Asynchronous Execution Listener Functioning Binding Procedure Interception Over file inputs payload change trigger point context handler callback wrapper methodology
    const mainImgSelectionUploadFieldNodeAreaPointHookContainer= document.getElementById('eventImgInput');
    const dynamicWaitBtnIndicatorLabelRefSwapWrapperLogicElementComponent = document.getElementById('publishBtnMain');
    
    if(mainImgSelectionUploadFieldNodeAreaPointHookContainer){
       mainImgSelectionUploadFieldNodeAreaPointHookContainer.addEventListener('change', async function(ev) {
            
            const mediaObjectFileNativeInputtedOriginalContainerSourceWrapperTargetInstanceStream  = ev.target.files[0];
            
            // Abort intercept checks fallback 
            if (!mediaObjectFileNativeInputtedOriginalContainerSourceWrapperTargetInstanceStream) { return; } 

            try {
                // Front end safety toggle UI logic handler mapping binding state update visual presentation representation update
                dynamicWaitBtnIndicatorLabelRefSwapWrapperLogicElementComponent.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Reducing Payload Format Computation Background... Wait!';
                dynamicWaitBtnIndicatorLabelRefSwapWrapperLogicElementComponent.disabled = true;

                console.log(`Original upload chunk file blob packet physical mapping density limit scale dimension representation bounds measured allocation limit estimate recorded parameters metadata logged value metric bounds checked parameter scope bounds observed index limits mapped limits parameters constraints size details data properties check boundaries defined calculated logged context bound data context observed: ${mediaObjectFileNativeInputtedOriginalContainerSourceWrapperTargetInstanceStream.size / 1024 / 1024} Megabytes`);
                
                // Set parameters limiting overhead scope configuration processing parameters initialization properties declaration limit constants setting configuration instantiation assignment value setting constraint
                const configCompressPropsLimitationSetTargetPropertiesConfigurationConstraintLimitVariableInstantiationOverheadsParameterSettingConstantContextBound= { 
                   maxSizeMB: 0.35, /* Sets strict 350kb Max output payload buffer generation constraints */
                   maxWidthOrHeight: 1100, 
                   useWebWorker: true,
                }
                
                // Awaiting asynchronous generation completion pipeline resolving execution thread synchronization block await wrapper instance object process callback invocation routine resolver resolution procedure binding mechanism execution loop cycle event callback
                const newlyResampledConstructedGeneratedMediaContainerBlobObjectImageInstanceResolvedProcessResponseWaitCycleOver= await imageCompression(mediaObjectFileNativeInputtedOriginalContainerSourceWrapperTargetInstanceStream, configCompressPropsLimitationSetTargetPropertiesConfigurationConstraintLimitVariableInstantiationOverheadsParameterSettingConstantContextBound);
                
                console.log(`Process Completed Check Response Payload Resolution Return Results Process Result Feedback Diagnostic Analytics Logs Results Trace Feedback Trace Resolution Payload Final Target Generation Process Metrics Recorded Size Process Success Resolved Mapped Diagnostic Target Success Completed Size Result Mapped Bound Value Size Computed Response Limits Evaluated Success Bound Context Computed Evaluation Recorded Diagnostic Completion Evaluated Resolution Process Returned Trace: ${newlyResampledConstructedGeneratedMediaContainerBlobObjectImageInstanceResolvedProcessResponseWaitCycleOver.size / 1024 / 1024} Megabytes Optimized Result File Size Metrics Data Properties Process Execution Logging Pipeline Information Flow Debug Evaluation Checks Success Flow Result Outputs Metric Resolution Bound Information Analytics Computed Metric Evaluation Mapped Mapped Results Flow Return Value Completion Computed Mapped Outputs Values Calculated Data Metrics Mapped Calculated Limit Feedback Output Returns Feedback Logging Success`); 

                // Creating dummy data transfer to forcefully reallocate original target values dynamically programatically mapped over payload boundaries defined configuration limit settings parameters value substitution overwriting object instantiation reassignments logic configuration variables over bindings assignment logic parameters values binding substitutions context limit mappings 
                const syntheticTemporaryPayloadSubstitutionLogicHelperWrapperUtilityMechanismInstatiationFunctionUtilityBuilderToolHelperRoutineConstructorGeneratorContainerTransferWrapper = new DataTransfer(); 
                
                // Encapsulate blob array metadata instantiation assignment values over attributes bindings configuration limits substitution binding references replacement payload context value parameter binding properties parameter replacement limits mappings initialization parameters configuration properties variable variable
                const resultingComputedOverrittenOptimisedPreparedTargetMediaReadyDeploymentInstanceMappedGeneratedPreparedPayloadFinalVersionReadyExecutionParameterContextVariables = new File([newlyResampledConstructedGeneratedMediaContainerBlobObjectImageInstanceResolvedProcessResponseWaitCycleOver], "optm_web_" + mediaObjectFileNativeInputtedOriginalContainerSourceWrapperTargetInstanceStream.name, {type: newlyResampledConstructedGeneratedMediaContainerBlobObjectImageInstanceResolvedProcessResponseWaitCycleOver.type});
                
                // Add it
                syntheticTemporaryPayloadSubstitutionLogicHelperWrapperUtilityMechanismInstatiationFunctionUtilityBuilderToolHelperRoutineConstructorGeneratorContainerTransferWrapper.items.add(resultingComputedOverrittenOptimisedPreparedTargetMediaReadyDeploymentInstanceMappedGeneratedPreparedPayloadFinalVersionReadyExecutionParameterContextVariables);
                
                // Overwrite original form inputs dynamically re-mapped reassignments references value definitions value bounds substitution value constraints binding
                mainImgSelectionUploadFieldNodeAreaPointHookContainer.files = syntheticTemporaryPayloadSubstitutionLogicHelperWrapperUtilityMechanismInstatiationFunctionUtilityBuilderToolHelperRoutineConstructorGeneratorContainerTransferWrapper.files; 
                
                 dynamicWaitBtnIndicatorLabelRefSwapWrapperLogicElementComponent.innerHTML = 'Publish To System Homepage Boards securely <i class="fas fa-bolt ms-1"></i>';
                 dynamicWaitBtnIndicatorLabelRefSwapWrapperLogicElementComponent.disabled = false;

            } catch (errCaptureBlockHandlingCatchWrapperInvocationCatchCycleExecutionProcedureFallbackResolverRecoveryErrorHandlerExceptionLogWrapperFallbackFeedbackLogicCatchInstanceHandlingEvaluationFallbackOutputContextResolverDiagnosticAnalyticsLogsFeedbackError) {
                  console.error(errCaptureBlockHandlingCatchWrapperInvocationCatchCycleExecutionProcedureFallbackResolverRecoveryErrorHandlerExceptionLogWrapperFallbackFeedbackLogicCatchInstanceHandlingEvaluationFallbackOutputContextResolverDiagnosticAnalyticsLogsFeedbackError);
                  dynamicWaitBtnIndicatorLabelRefSwapWrapperLogicElementComponent.innerHTML = 'Warning Upload Computation Process Resolution Exception Try Reinsert Component Logic Evaluation Feedback Flow Exception Caught Resolver Return Data Bind Parameters Reattach Again <i class="fas fa-bolt ms-1"></i>';
                  dynamicWaitBtnIndicatorLabelRefSwapWrapperLogicElementComponent.disabled = false;
            }
       }); 
    }
</script>

<?php include '../layout/footer.php'; ?>