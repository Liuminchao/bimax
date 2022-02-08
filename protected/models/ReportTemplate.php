<?php

/**
 * 报告模板
 * @author LiuMinchao
 */
class ReportTemplate extends CActiveRecord {
    public static function ptwShsdList(){
        $list = array(
            "SHSD001" => array(
                "title" => 'STEC- OSHE - 001',
                "stage" => array(
                    "title" => "WORKING AT HEIGHT (> 2M) PERMIT",
                    "dept" => "Contractor/Dept",
                    "location" => "Location of Work",
                    "description" => "Description of Works",
                    "permit_date" => "Permit Validity Period<br>Date / Time:",
                    "note" => "This permit is valid only for the period stated above.  The condition of issues must be complied with throughout the duration of the work.  This permit may be withdrawn at any time if non-compliance is observed.  You are responsible for the copy of this permit to display at work location and must produce it on request."
                ),
                "stage1" => array(
                    "title" => "STAGE 1 – PERMIT REQUESTER / APPLICANT (STEC's Staff / Sub-con)",
                    "APPLICANT NAME:" => "APPLICANT NAME:  ",
                    "DATE / TIME:" => "DATE / TIME:",
                    "SIGNATURE:" => "SIGNATURE:",
                    "STEC IN-CHARGE:" => "STEC IN-CHARGE:",
                    "SIGNATURE:" => "SIGNATURE:",
                    "note" => "I have checked and confirmed that the above safety requirements have been complied with. I fully understand the nature of the work & hazard associated with the work. I will supervise the above work and undertake to stop / ceases the operation should there be  any unsafe condition or potential hazardous situation arose during the course of work.",
                    "remark" => "State Other Safety Requirements:"
                ),
                "stage2" => array(
                    "title" => "STAGE 2 – EVALUATION & ENDORSED BY SUB CON SAFETY PERSONNEL",
                    "note" => "I have assessed / inspected together with the applicant for the above-stated location and confirmed that the recommended safety measures are in place and the said location is safe for work.",
                    "NAME:" => "NAME:",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME:",
                    "SIGNATURE:" => "SIGNATURE:"
                ),
                "stage3" => array(
                    "title" => "STAGE 3 – EVALUATION & ENDORSED BY STEC WAH SAFETY ASSESSOR",
                    "note" => "I have witnessed together with the applicant for the above-stated location and confirmed that the recommended safety measures are in place and the said location is safe for work.",
                    "NAME:" => "NAME",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME:",
                    "SIGNATURE:" => "SIGNATURE:"
                ),
                "stage4" => array(
                    "title" => "STAGE 4 –APPROVED */ <del>NOT APPROVED *</del>: BY PROJECT / CONSTRUCTION / STATION / TUNNEL MANAGER",
                    "note" => "I am fully satisfied that reasonably practicable measures have been implemented and enforced. This permit will be revoked immediately & work shall be suspended if any incompatible work observed or reported.",
                    "NAME:" => "NAME:",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME:",
                    "SIGNATURE:" => "SIGNATURE:"
                ),
                "stage5" => array(
                    "title" => "STAGE 5 – WORK COMPLETION REPORT (Applicant to close and return the permit to STEC WSHE Department)",
                    "The above work was completed on (date)" => "The above work was completed on (date) _____at (Time)______hrs",
                    "NAME:_last" => "NAME:",
                    "SIGNATURE:_last" => "SIGNATURE:",
                    "DATE / TIME:_last" => "DATE / TIME:"
                )
            ),
            "SHSD002" => array(
                "title" => "STEC-OSHE-002",
                "stage" => array(
                    "title" => "CONFINED SPACE ENTRY PERMIT",
                    "dept" => "Contractor/Dept",
                    "location" => "Location of Work",
                    "description" => "Description of Works",
                    "permit_date" => "Permit Validity Period<br>Date / Time:",
                    "note" => "This permit is valid only for the period stated above. The condition of issues must be complied with throughout the duration of the work. You are responsible for the copy of this permit to display at work location and must produce it on request."
                ),
                "stage1" => array(
                    "title" => "STAGE 1 – PERMIT REQUESTER / APPLICANT (STEC’s Staff / Sub-con)",
                    "APPLICANT NAME:" => "APPLICANT NAME:  ",
                    "DATE / TIME:" => "DATE / TIME:",
                    "SIGNATURE:" => "SIGNATURE:",
                    "STEC IN-CHARGE:" => "STEC IN-CHARGE:",
                    "SIGNATURE:" => "SIGNATURE:",
                    "note" => "I have checked and confirmed that the above safety requirements have been complied with. I fully understand the nature of the work & hazard associated with the work. I will supervise the above work and undertake to stop / ceases the operation should there be any unsafe condition or potential hazardous situation arose during the course of work.",
                    "remark" => "State Other Safety Requirements:"
                ),
                "stage2" => array(
                    "title" => "STAGE 2 – EVALUATION & ENDORSED BY CONFINED SPACE ASSESSOR/COMPETENT PERSON",
                    "note" => "I have assessed / inspected together with the applicant for the above-stated location and confirmed that the recommended safety measures are in place and the said location is safe for work. (Refer to gas monitoring checklist)",
                    "NAME:" => "NAME:",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME:",
                    "SIGNATURE:" => "SIGNATURE:"
                ),
                "stage3" => array(
                    "title" => "STAGE 3 – EVALUATION & ENDORSED BY SUB CON SAFETY PERSONNEL",
                    "note" => "I have assessed / inspected together with the applicant for the above-stated location and confirmed that the recommended safety measures are in place and the said location is safe for work.",
                    "NAME:" => "NAME:",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME:",
                    "SIGNATURE:" => "SIGNATURE:"
                ),
                "stage4" => array(
                    "title" => "STAGE 4 – EVALUATION & ENDORSED BY STEC SAFETY ASSESSOR",
                    "note" => "I have witnessed together with the applicant for the above-stated location and confirmed that the recommended safety measures are in place and the said location is safe for work.",
                    "NAME:" => "NAME:",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME:",
                    "SIGNATURE:" => "SIGNATURE:"
                ),
                "stage5" => array(
                    "title" => "STAGE 5 –APPROVED */ <del>NOT APPROVED *</del>: BY PROJECT / CONSTRUCTION / STATION / TUNNEL MANAGER",
                    "note" => "I am fully satisfied that reasonably practicable measures have been implem& work shall be suspended if any incompatible work observed or reported.",
                    "NAME:" => "NAME:",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME:",
                    "SIGNATURE:" => "SIGNATURE:"
                ),
                "stage6" => array(
                    "title" => "STAGE 6 – WORK COMPLETION REPORT (Applicant to close and return the permit to STEC WSHE Department)",
                    "The above work was completed on (date)" => "The above work was completed on (date) _____at (Time)______hrs",
                    "NAME:_last" => "NAME:",
                    "SIGNATURE:_last" => "SIGNATURE:",
                    "DATE / TIME:_last" => "DATE / TIME:"
                )
            ),
            "SHSD003" => array(
                "title" => "STEC-OSHE-003",
                "stage" => array(
                    "title" => "EXCAVATION PERMIT",
                    "dept" => "Contractor/Dept",
                    "location" => "Location of Work",
                    "description" => "Description of Works",
                    "permit_date" => "Permit Validity Period<br>Date / Time:",
                    "note" => "This permit is valid only for the period stated above. The condition of issues must be complied with throughout the duration of the work. This permit may be withdrawn at any time if non-compliance is observed. You are responsible for the copy of this permit to display at work location and must produce it on request.",
                ),
                "stage1" => array(
                    "title" => "STAGE 1 – PERMIT REQUESTER / APPLICANT (STEC’s Staff / Sub-con)",
                    "APPLICANT NAME:" => "APPLICANT NAME:  ",
                    "DATE / TIME:" => "DATE / TIME:",
                    "SIGNATURE:" => "SIGNATURE:",
                    "STEC IN-CHARGE:" => "STEC IN-CHARGE:",
                    "SIGNATURE:" => "SIGNATURE:",
                    "note" => "I have checked and confirmed that the above safety requirements have been complied with. I fully understand the nature of the work & hazard associated with the work. I will supervise the above work and undertake to stop / ceases the operation should there be any unsafe condition or potential hazardous situation arose during the course of work.",
                    "remark" => "State Other Safety Requirements:"
                ),
                "stage2" => array(
                    "title" => "STAGE 2 – EVALUATION & ENDORSED BY CONTRACTOR UTILITY ENGINEER (CUE)",
                    "checklist" => "checklist",
                    "note" => "I have assessed / inspected together with the applicant for the above stated location and confirmed that the recommended safety measures are in place and the said location is safe for work based on the latest utility drawing no.: as per attached.",
                    "NAME:" => "NAME:",
                    "HANDPHONE / CONTACT NO. :" => "HANDPHONE / CONTACT NO. :",
                    "DATE / TIME:" => "DATE / TIME:",
                    "SIGNATURE:" => "SIGNATURE:"
                ),
                "stage3" => array(
                    "title" => "STAGE 3 – EVALUATION & ENDORSED BY SUB CON SAFETY PERSONNEL",
                    "note" => "I have assessed / inspected together with the applicant for thmeasures are in place and the said location is safe for work.",
                    "NAME:" => "NAME:",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME:",
                    "SIGNATURE:" => "SIGNATURE:"
                ),
                "stage4" => array(
                    "title" => "STAGE 4 – EVALUATION & ENDORSED BY STEC SAFETY ASSESSOR",
                    "note" => "I have witnessed together with the applicant for the above-stated location and confirmed that the recommended safety measures are in place and the said location is safe for work.",
                    "NAME:" => "NAME:",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME:",
                    "SIGNATURE:" => "SIGNATURE:"
                ),
                "stage5" => array(
                    "title" => "STAGE 5 –APPROVED */ <del><del>NOT APPROVED *</del></del>: BY PROJECT / CONSTRUCTION / STATION / TUNNEL MANAGER",
                    "note" => "I am fully satisfied that reasonably practicable measures have been implemented and enforced. This permit will be revoked immediately & work shall be suspended if any incompatible work observed or reported.",
                    "NAME:" => "NAME:",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME:",
                    "SIGNATURE:" => "SIGNATURE:"
                ),
                "stage6" => array(
                    "title" => "STAGE 6 – WORK COMPLETION REPORT (Applicant to close and return the permit to STEC WSHE Department)",
                    "The above work was completed on (date)" => "The above work was completed on (date) _____at (Time)______hrs",
                    "NAME:_last" => "NAME:",
                    "SIGNATURE:_last" => "SIGNATURE:",
                    "DATE / TIME:_last" => "DATE / TIME:"
                )
            ),
            "SHSD004" => array(
                "title" => "STEC-OSHE-004",
                "stage" => array(
                    "title" => "HOT WORK PERMIT (Gas Cutting / Welding)",
                    "dept" => "Contractor/Dept",
                    "location" => "Location of Work",
                    "description" => "Description of Works",
                    "permit_date" => "Permit Validity Period<br>Date / Time:",
                    "note" => "This permit is valid only for the period stated above. The condition of issues must be complied with throughout the duration of the work. This permit may be withdrawn at any time if non-compliance is observed. You are responsible for the copy of this permit to display at work location and must produce it on request."
                ),
                "stage1" => array(
                    "title" => "STAGE 1 – PERMIT REQUESTER / APPLICANT (STEC’s Staff / Sub-con)",
                    "APPLICANT NAME:" => "APPLICANT NAME:  ",
                    "DATE / TIME:" => "DATE / TIME:  ",
                    "SIGNATURE:" => "SIGNATURE:  ",
                    "STEC IN-CHARGE:" => "STEC IN-CHARGE:  ",
                    "SIGNATURE:" => "SIGNATURE:  ",
                    "note" => "I have checked and confirmed that the above safety requirements have been com& hazard associated with the work. I will supervise the above work and undertake unsafe condition or potential hazardous situation arose during the course of work",
                    "remark" => "State Other Safety Requirements:"
                ),
                "stage2" => array(
                    "title" => "STAGE 2 – EVALUATION & ENDORSED BY SUB CON SAFETY PERSONNEL",
                    "note" => "I have assessed / inspected together with the applicant for the measures are in place and the said location is safe for work.",
                    "NAME:" => "NAME:  ",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME:  ",
                    "SIGNATURE:" => "SIGNATURE:  "
                ),
                "stage3" => array(
                    "title" => "STAGE 3 – EVALUATION & ENDORSED BY STEC SAFETY ASSESSOR",
                    "note" => "I have witnessed together with the applicant for in place and the said location is safe for work.",
                    "NAME:" => "NAME:  ",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME:  ",
                    "SIGNATURE:" => "SIGNATURE:  "
                ),
                "stage4" => array(
                    "title" => "STAGE 4 –APPROVED */ <del><del>NOT APPROVED *</del></del>: BY PROJECT / CONSTRUCTION / STATION / TUNNEL MANAGER",
                    "note" => "I am fully satisfied that reasonably practicable measures have been implemented and enforced. This permit will be revoked immediately & work shall be suspended if any incompatible work observed or reported.",
                    "NAME:" => "NAME:  ",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME:  ",
                    "SIGNATURE:" => "SIGNATURE:  "
                ),
                "stage5" => array(
                    "title" => "STAGE 5 – WORK COMPLETION REPORT (Applicant to close and return the permit to STEC WSHE Department)",
                    "The above work was completed on (date)" => "The above work was completed on (date) _____at (Time)______hrs",
                    "NAME:_last" => "NAME:  ",
                    "SIGNATURE:_last" => "SIGNATURE:  ",
                    "DATE / TIME:_last" => "DATE / TIME:  "
                )
            ),
            "SHSD005" => array(
                "title" => "STEC-OSHE-005",
                "stage" => array(
                    "title" => "PILING PERMIT (LM :",
                    "dept" => "Contractor/Dept",
                    "location" => "Location of Work<br>(Building/Storey/Work Area)",
                    "description" => "Description of Works",
                    "permit_date" => "Permit Validity Period<br>Date / Time:",
                    "note" => "This permit is valid only for the period stated above. The condition of issues must be complied with throughout the duration of the work. This permit may be withdrawn at any time if non-compliance is observed. You are responsible for the copy of this permit to display at work location and must produce it on request."
                ),
                "stage1" => array(
                    "title" => "STAGE 1 – PERMIT REQUESTER / APPLICANT (STEC’s Staff / Sub-con)",
                    "APPLICANT NAME:" => "APPLICANT NAME:  ",
                    "DATE / TIME:" => "DATE / TIME:  ",
                    "SIGNATURE:" => "SIGNATURE:  ",
                    "STEC IN-CHARGE:" => "STEC IN-CHARGE:  ",
                    "SIGNATURE:" => "SIGNATURE:  ",
                    "note" => "I have checked and confirmed that the above safety requirements have been complied with. I fully understand the nature of the work & hazard associated with the work. I will supervise the above work and undertake to stop / ceases the operation should there be any unsafe condition or potential hazardous situation arose during the course of work.",
                    "remark" => "State Other Safety Requirements:"
                ),
                "stage2" => array(
                    "title" => "STAGE 2 – EVALUATION & ENDORSED BY SUB CON SAFETY PERSONNEL",
                    "note" => "I have assessed / inspected together with the applicant for the above-stated location and confirmed that the recommended safety measures are in place and the said location is safe for work.",
                    "NAME:" => "NAME:  ",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME:  ",
                    "SIGNATURE:" => "SIGNATURE:  "
                ),
                "stage3" => array(
                    "title" => "STAGE 3 – EVALUATION & ENDORSED BY STEC SAFETY ASSESSOR",
                    "note" => "I have witnessed together with the applicant for the above-stated location and confirmed that the recommended safety measures are in place and the said location is safe for work.",
                    "NAME:" => "NAME:  ",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME:  ",
                    "SIGNATURE:" => "SIGNATURE:  "
                ),
                "stage4" => array(
                    "title" => "STAGE 4 –APPROVED */ <del>NOT APPROVED *</del>: BY PROJECT / CONSTRUCTION / STATION / TUNNEL MANAGER",
                    "note" => "I am fully satisfied that reasonably practicable measures have been implemented and enforced. This permit will be revoked immediately & work shall be suspended if any incompatible work observed or reported.",
                    "NAME:" => "NAME:  ",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME:  ",
                    "SIGNATURE:" => "SIGNATURE:  "
                ),
                "stage5" => array(
                    "title" => "STAGE 5 – WORK COMPLETION REPORT (Applicant to close and return the permit to STEC WSHE Department)",
                    "The above work was completed on (date)" => "The above work was completed on (date) _____at (Time)______hrs",
                    "NAME:_last" => "NAME:  ",
                    "SIGNATURE:_last" => "SIGNATURE:  ",
                    "DATE / TIME:_last" => "DATE / TIME:  "
                )
            ),
            "SHSD006" => array(
                "title" => "STEC-OSHE-006",
                "stage" => array(
                    "title" => "LIFTING PERMIT (LM:",
                    "dept" => "Contractor/Dept",
                    "location" => "Location of Work<br>(Building/Storey/Work Area)",
                    "description" => "Description of Works",
                    "permit_date" => "Permit Validity Period<br>Date / Time:",
                    "note" => "This permit is valid only for the period stated above. The condition of issues must be complied with throughout the duration of the work. This permit may be withdrawn at any time if non-compliance is observed. You are responsible for the copy of this permit to display at work location and must produce it on request.",
                ),
                "stage1" => array(
                    "title" => "STAGE 1 – PERMIT REQUESTER / APPLICANT (STEC’s Staff / Sub-con)",
                    "APPLICANT NAME:" => "APPLICANT NAME: ",
                    "DATE / TIME:" => "DATE / TIME: ",
                    "SIGNATURE:" => "SIGNATURE: ",
                    "STEC IN-CHARGE:" => "STEC IN-CHARGE: ",
                    "SIGNATURE:" => "SIGNATURE: ",
                    "note" => "I have checked and confirmed that the above safety requirements have been complied with. I fully understand the nature of the work & hazard associated with the work. I will supervise the above work and undertake to stop / ceases the operation should there be any unsafe condition or potential hazardous situation arose during the course of work.",
                    "remark" => "State Other Safety Requirements:"
                ),
                "stage2" => array(
                    "title" => "STAGE 2 – EVALUATION & ENDORSED BY SUB CON SAFETY PERSONNEL",
                    "note" => "I have assessed / inspected together with the applicant for the above-stated location and confirmed that the recommended safety measures are in place and the said location is safe for work.",
                    "NAME:" => "NAME: ",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME: ",
                    "SIGNATURE:" => "SIGNATURE: "
                ),
                "stage3" => array(
                    "title" => "STAGE 3 – EVALUATION & ENDORSED BY STEC SAFETY ASSESSOR",
                    "note" => "I have witnessed together with the applicant for the above-stated location and confirmed that the recommended safety measures are in place and the said location is safe for work.",
                    "NAME:" => "NAME: ",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME: ",
                    "SIGNATURE:" => "SIGNATURE: "
                ),
                "stage4" => array(
                    "title" => "STAGE 4 –APPROVED */ <del>NOT APPROVED *</del>: BY PROJECT / CONSTRUCTION / STATION / TUNNEL MANAGER",
                    "note" => "I am fully satisfied that reasonably practicable measures have been implemented and enforced. This permit will be revoked immediately & work shall be suspended if any incompatible work observed or reported.",
                    "NAME:" => "NAME: ",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME: ",
                    "SIGNATURE:" => "SIGNATURE: "
                ),
                "stage5" => array(
                    "title" => "STAGE 5 – WORK COMPLETION REPORT (Applicant to close and return the permit to STEC WSHE Department)",
                    "The above work was completed on (date)" => "The above work was completed on (date) _____at (Time)______hrs",
                    "NAME:_last" => "NAME: ",
                    "SIGNATURE:_last" => "SIGNATURE: ",
                    "DATE / TIME:_last" => "DATE / TIME: "
                )
            ),
            "SHSD007" => array(
                "title" => "STEC-OSHE-007",
                "stage" => array(
                    "checklist" => "checklist",
                    "title" => "ENTRY TO CUTTER HEAD PERMIT",
                    "dept" => "Contractor/Dept",
                    "location" => "Location of Work",
                    "description" => "Description of Works",
                    "permit_date" => "Permit Validity Period<br>Date / Time:",
                    "air_pressure" => array(
                        "Compressed Air Pressure" => "Compressed Air Pressure",
                        "Recommended Initial Pressure" => "",
                        "Minimum Initial Pressure" => ""
                    ),
                    "note" => "This permit is valid only for the period stated above. The condition of issues must be complied with throughout the duration of the work. This permit may be withdrawn at any time if non-compliance is observed. You are responsible for the copy of this permit to display at work location and must produce it on request."
                ),
                "stage1" => array(
                    "title" => "STAGE 1 – PERMIT REQUESTER / APPLICANT (STEC’s Staff / Sub-con)",
                    "APPLICANT NAME:" => "APPLICANT NAME: ",
                    "DATE / TIME:" => "DATE / TIME: ",
                    "SIGNATURE:" => "SIGNATURE: ",
                    "STEC IN-CHARGE:" => "STEC IN-CHARGE: ",
                    "SIGNATURE:" => "SIGNATURE: ",
                    "note" => "I have checked and confirmed that the above safety requirements have been complied with. I fully understand the nature of the work & hazard associated with the work. I will supervise the above work and undertake to stop / ceases the operation should there be any unsafe condition or potential hazardous situation arose during the course of work.",
                    "remark" => "State Other Safety Requirements:"
                ),
                "stage2" => array(
                    "title" => "STAGE 2 –APPROVED */ <del>NOT APPROVED *</del>: BY SENIOR TUNNEL MANAGER / TUNNEL MANAGER",
                    "note" => "I am fully satisfied that reasonably practicable measures have been implemented and enforced. This permit will be revoked immediately & work shall be suspended if any incompatible work observed or reported.",
                    "NAME:" => "NAME: ",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME: ",
                    "SIGNATURE:" => "SIGNATURE: "
                ),
                "stage3" => array(
                    "title" => "STAGE 3 – ACKNOWLEDGED BY QP(S) / LTA",
                    "Checker_NAME:" => "NAME: ",
                    "SIGN :" => "SIGN : ",
                    "DATE / TIME:" => "DATE / TIME: "
                ),
                "stage4" => array(
                    "title" => "STAGE 4 – WORK COMPLETION REPORT (Applicant to close and return the permit to STEC WSHE Department)",
                    "The above work was completed on (date)" => "The above work was completed on (date) _____at (Time)______hrs",
                    "NAME:_last" => "NAME: ",
                    "SIGNATURE:_last" => "SIGNATURE: ",
                    "DATE / TIME:_last" => "DATE / TIME: "
                )
            ),
            "SHSD008" => array(
                "title" => "STEC-OSHE-008",
                "stage" => array(
                    "title" => "TRACK OCCUPATION PERMIT",
                    "dept" => "Contractor/Dept",
                    "location_1" => array(
                        "Location of Work" => "Location of Work",
                        "Tunnel: From Ring No." => "Tunnel: "
                    ),
                    "description" => "Description of Works:",
                    "permit_date" => "Permit Validity Period<br>Date / Time:",
                    "note" => "This permit is valid only for the period stated above. The condition of issues must be complied with throughout the duration of the work. This permit may be withdrawn at any time if non-compliance is observed. You are responsible for the copy of this permit to display at work location and must produce it on request."
                ),
                "stage1" => array(
                    "title" => "STAGE 1 – PERMIT REQUESTER / APPLICANT (STEC’s Staff / Sub-con)",
                    "APPLICANT NAME:" => "APPLICANT NAME: ",
                    "DATE / TIME:" => "DATE / TIME: ",
                    "SIGNATURE:" => "SIGNATURE: ",
                    "STEC IN-CHARGE:" => "STEC IN-CHARGE: ",
                    "SIGNATURE:" => "SIGNATURE: ",
                    "note" => "I have checked and confirmed that the above safety requirements have been complied with. I fully understand the nature of the work & hazard associated with the work. I will supervise the above work and undertake to stop / ceases the operation should there be any unsafe condition or potential hazardous situation arose during the course of work.",
                    "remark" => "State Other Safety Requirements:"
                ),
                "stage2" => array(
                    "title" => "STAGE 2 – EVALUATION & ENDORSED BY SUB CON SAFETY PERSONNEL",
                    "note" => "I have assessed / inspected together with the applicant for the above-stated location and confirmed that the recommended safety measures are in place and the said location is safe for work.",
                    "NAME:" => "NAME: ",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME: ",
                    "SIGNATURE:" => "SIGNATURE: "
                ),
                "stage3" => array(
                    "title" => "STAGE 3 – EVALUATION & ENDORSED BY STEC SAFETY ASSESSOR",
                    "note" => "I have witnessed together with the applicant for the above-stated location and confirmed that the recommended safety measures are in place and the said location is safe for work.",
                    "NAME:" => "NAME: ",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME: ",
                    "SIGNATURE:" => "SIGNATURE: "
                ),
                "stage4" => array(
                    "title" => "STAGE 4 –APPROVED */ <del>NOT APPROVED *</del>: BY PROJECT / CONSTRUCTION / STATION / TUNNEL MANAGER",
                    "note" => "I am fully satisfied that reasonably practicable measures have been implemented and enforced. This permit will be revoked immediately & work shall be suspended if any incompatible work observed or reported.",
                    "NAME:" => "NAME: ",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME: ",
                    "SIGNATURE:" => "SIGNATURE: "
                ),
                "stage5" => array(
                    "title" => "STAGE 4 –APPROVED */ <del>NOT APPROVED *</del>: BY PROJECT / CONSTRUCTION / STATION / TUNNEL MANAGER",
                    "The above work was completed on (date)" => "The above work was completed on (date) _____at (Time)______hrs",
                    "NAME:_last" => "NAME: ",
                    "SIGNATURE:_last" => "SIGNATURE: ",
                    "DATE / TIME:_last" => "DATE / TIME: "
                )
            ),
            "SHSD009" => array(
                "title" => "STEC-OSHE-009",
                "stage" => array(
                    "title" => "BLASTING PERMIT",
                    "dept" => "Contractor/Dept",
                    "location" => "Location of Work",
                    "description" => "Description of Works",
                    "permit_date" => "Permit Validity Period<br>Date / Time:",
                    "note" => "This permit is valid only for the period stated above. The condition of issues must be complied with throughout the duration of the work. This permit may be withdrawn at any time if non-compliance is observed. You are responsible for the copy of this permit to display at work location and must produce it on request."
                ),
                "stage1" => array(
                    "title" => "STAGE 1 – PERMIT REQUESTER / APPLICANT (STEC’s Staff / Sub-con)",
                    "APPLICANT NAME:" => "APPLICANT NAME: ",
                    "DATE / TIME:" => "DATE / TIME: ",
                    "SIGNATURE:" => "SIGNATURE: ",
                    "STEC IN-CHARGE:" => "STEC IN-CHARGE: ",
                    "SIGNATURE:" => "SIGNATURE: ",
                    "note" => "I have checked and confirmed that the above safety requirements have been complied with. I fully understand the nature of the work & hazard associated with the work. I will supervise the above work and undertake to stop / ceases the operation should there be any unsafe condition or potential hazardous situation arose during the course of work.",
                    "remark" => "State Other Safety Requirements:"
                ),
                "stage2" => array(
                    "title" => "STAGE 2 – EVALUATION & ENDORSED BY SUB CON SAFETY PERSONNEL",
                    "note" => "I have assessed / inspected together with the applicant for the above-stated location and confirmed that the recommended safety measures are in place and the said location is safe for work.",
                    "NAME:" => "NAME: ",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME: ",
                    "SIGNATURE:" => "SIGNATURE: "
                ),
                "stage3" => array(
                    "title" => "STAGE 3 – EVALUATION & ENDORSED BY STEC SAFETY ASSESSOR",
                    "note" => "I have witnessed together with the applicant for the above-stated location and confirmed that the recommended safety measures are in place and the said location is safe for work.",
                    "NAME:" => "NAME: ",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME: ",
                    "SIGNATURE:" => "SIGNATURE: "
                ),
                "stage4" => array(
                    "title" => "STAGE 4 –APPROVED */ <del>NOT APPROVED *</del>: BY PROJECT / CONSTRUCTION / STATION / TUNNEL MANAGER",
                    "note" => "I am fully satisfied that reasonably practicable measures have been implemented and enforced. This permit will be revoked immediately & work shall be suspended if any incompatible work observed or reported.",
                    "NAME:" => "NAME: ",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME: ",
                    "SIGNATURE:" => "SIGNATURE: "
                ),
                "stage5" => array(
                    "title" => "STAGE 5 – WORK COMPLETION REPORT (Applicant to close and return the permit to STEC WSHE Department)",
                    "The above work was completed on (date)" => "The above work was completed on (date) _____at (Time)______hrs",
                    "NAME:_last" => "NAME: ",
                    "SIGNATURE:_last" => "SIGNATURE: ",
                    "DATE / TIME:_last" => "DATE / TIME: "
                )
            ),
            "SHSD010" => array(
                "title" => "STEC-OSHE-010",
                "stage" => array(
                    "title" => "DEMOLITION PERMIT",
                    "dept" => "Contractor/Dept",
                    "location" => "Location of Work<br>(Building/Storey/Work Area)",
                    "description" => "Description of Works",
                    "permit_date" => "Permit Validity Period<br>Date / Time:",
                    "note" => "This permit is valid only for the period stated above. The condition of issues must be complied with throughout the duration of the work. This permit may be withdrawn at any time if non-compliance is observed. You are responsible for the copy of this permit to display at work location and must produce it on request."
                ),
                "stage1" => array(
                    "title" => "STAGE 1 – PERMIT REQUESTER / APPLICANT (STEC’s Staff / Sub-con)",
                    "APPLICANT NAME:" => "APPLICANT NAME: ",
                    "DATE / TIME:" => "DATE / TIME: ",
                    "SIGNATURE:" => "SIGNATURE: ",
                    "STEC IN-CHARGE:" => "STEC IN-CHARGE: ",
                    "SIGNATURE:" => "SIGNATURE: ",
                    "note" => "I have checked and confirmed that the above safety requirements have been complied with. I fully understand the nature of the work & hazard associated with the work. I will supervise the above work and undertake to stop / ceases the operation should there be any unsafe condition or potential hazardous situation arose during the course of work.",
                    "remark" => "State Other Safety Requirements:"
                ),
                "stage2" => array(
                    "title" => "STAGE 2 – EVALUATION & ENDORSED BY SUB CON SAFETY PERSONNEL",
                    "note" => "I have assessed / inspected together with the applicant for the above-stated location and confirmed that the recommended safety measures are in place and the said location is safe for work.",
                    "NAME:" => "NAME: ",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME: ",
                    "SIGNATURE:" => "SIGNATURE: "
                ),
                "stage3" => array(
                    "title" => "STAGE 3 – EVALUATION & ENDORSED BY STEC SAFETY ASSESSOR",
                    "note" => "I have witnessed together with the applicant for the above-stated location and confirmed that the recommended safety measures are in place and the said location is safe for work.",
                    "NAME:" => "NAME: ",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME: ",
                    "SIGNATURE:" => "SIGNATURE: "
                ),
                "stage4" => array(
                    "title" => "STAGE 4 –APPROVED */ <del>NOT APPROVED *</del>: BY PROJECT / CONSTRUCTION / STATION / TUNNEL MANAGER",
                    "note" => "I am fully satisfied that reasonably practicable measures have been implemented and enforced. This permit will be revoked immediately & work shall be suspended if any incompatible work observed or reported.",
                    "NAME:" => "NAME: ",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME: ",
                    "SIGNATURE:" => "SIGNATURE: "
                ),
                "stage5" => array(
                    "title" => "STAGE 5 – WORK COMPLETION REPORT (Applicant to close and return the permit to STEC WSHE Department)",
                    "The above work was completed on (date)" => "The above work was completed on (date) _____at (Time)______hrs",
                    "NAME:_last" => "NAME: ",
                    "SIGNATURE:_last" => "SIGNATURE: ",
                    "DATE / TIME:_last" => "DATE / TIME: "
                )
            ),
            "SHSD011" => array(
                "title" => "STEC-OSHE-011",
                "stage" => array(
                    "title" => "Heavy Machinery Shifting Permit",
                    "dept" => "Contractor/Dept",
                    "machine_no" => "Machine Registration No",
                    "machinery_movement" => array(
                        "Machinery Movement" => "Machinery Movement",
                        "Machinery Shifting From :" => "Machinery Shifting ",
                        "To:" => "To:"
                    ),
                    "permit_date" => "Permit Validity Period<br>Date / Time:",
                    "note" => "This permit is valid only for the period stated above. The condition of issues must be complied with throughout the duration of the work. This permit may be withdrawn at any time if non-compliance is observed. You are responsible for the copy of this permit to display at work location and must produce it on request.",
                ),
                "stage1" => array(
                    "title" => "STAGE 1 – PERMIT REQUESTER / APPLICANT (STEC’s Staff / Sub-con)",
                    "APPLICANT NAME:" => "APPLICANT NAME: ",
                    "DATE / TIME:" => "DATE / TIME: ",
                    "SIGNATURE:" => "SIGNATURE: ",
                    "STEC IN-CHARGE:" => "STEC IN-CHARGE: ",
                    "SIGNATURE:" => "SIGNATURE: ",
                    "note" => "I have checked and confirmed that the above safety requirements have been complied with. I fully understand the nature of the work & hazard associated with the work. I will supervise the above work and undertake to stop / ceases the operation should there be any unsafe condition or potential hazardous situation arose during the course of work.",
                    "remark" => "State Other Safety Requirements:"
                ),
                "stage2" => array(
                    "title" => "STAGE 2 – EVALUATION & ENDORSED BY SUB CON SAFETY PERSONNEL",
                    "note" => "I have assessed / inspected together with the applicant for the above-stated location and confirmed that the recommended safety measures are in place and the said location is safe for work.",
                    "NAME:" => "NAME: ",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME: ",
                    "SIGNATURE:" => "SIGNATURE: "
                ),
                "stage3" => array(
                    "title" => "STAGE 3 – EVALUATION & ENDORSED BY STEC SAFETY ASSESSOR",
                    "note" => "I have witnessed together with the applicant for the above-stated location and confirmed that the recommended safety measures are in place and the said location is safe for work.",
                    "NAME:" => "NAME: ",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME: ",
                    "SIGNATURE:" => "SIGNATURE: "
                ),
                "stage4" => array(
                    "title" => "STAGE 4 –APPROVED */ <del>NOT APPROVED *</del>: BY PROJECT / CONSTRUCTION / STATION / TUNNEL MANAGER",
                    "note" => "I am fully satisfied that reasonably practicable measures have been implemented and enforced. This permit will be revoked immediately & work shall be suspended if any incompatible work observed or reported.",
                    "NAME:" => "NAME: ",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME: ",
                    "SIGNATURE:" => "SIGNATURE: "
                ),
                "stage5" => array(
                    "title" => "STAGE 5 – QPs Acknowledgement",
                    "I, (NAME)" => "I, (NAME) ",
                    "Bck_DATE / TIME:" => "DATE / TIME: ",
                    "Checker_DESIGNATION:" => "DESIGNATION: ",
                    "Checker_SIGNATURE:" => "SIGNATURE: "
                ),
                "stage6" => array(
                    "title" => "STAGE 6 – WORK COMPLETION REPORT (Applicant to close and return the permit to STEC WSHE Department)",
                    "The above work was completed on (date)" => "The above work was completed on (date) _____at (Time)______hrs",
                    "NAME:_last" => "NAME: ",
                    "SIGNATURE:_last" => "SIGNATURE: ",
                    "DATE / TIME:_last" => "DATE / TIME: "
                )
            ),
            "SHSD012" => array(
                "title" => "STEC-OSHE-012",
                "stage" => array(
                    "title" => "GENERAL PERMIT TO WORK",
                    "dept" => "Contractor/Dept",
                    "location" => "Location of Work",
                    "description" => "Description of Works",
                    "permit_date" => "Permit Validity Period<br>Date / Time:",
                    "note" => "This permit is valid only for the period stated above. The condition of issues must be complied with throughout the duration of the work. This permit may be withdrawn at any time if any non-compliance is observed. You are responsible for displaying a copy of the approved permit at the work location and producing it upon request.",
                ),
                "stage1" => array(
                    "title" => "STAGE 1 – PERMIT REQUESTER / APPLICANT (STEC’s Staff / Sub-con)",
                    "APPLICANT NAME:" => "APPLICANT NAME: ",
                    "DATE / TIME:" => "DATE / TIME: ",
                    "SIGNATURE:" => "SIGNATURE: ",
                    "STEC IN-CHARGE:" => "STEC IN-CHARGE: ",
                    "SIGNATURE:" => "SIGNATURE: ",
                    "note" => "I have checked and confirmed that the above safety requirements have been complied with. I fully understand the nature of the work & hazard associated with the work. I will supervise the above work and undertake to stop / ceases the operation should there be any unsafe condition or potential hazardous situation arose during the course of work.",
                    "remark" => "State Other Safety Requirements:"
                ),
                "stage2" => array(
                    "title" => "STAGE 2 – EVALUATION & ENDORSED BY SUB CON SAFETY PERSONNEL",
                    "note" => "I have inspected and assessed, together with the applicant, for the above-stated location and confirm that the recommended safety measures are in place and the said location is safe for work.",
                    "NAME:" => "NAME: ",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME: ",
                    "SIGNATURE:" => "SIGNATURE: "
                ),
                "stage3" => array(
                    "title" => "STAGE 3 – EVALUATION & ENDORSED BY STEC SAFETY ASSESSOR",
                    "note" => "I have witnessed, together with the applicant, for the above-stated location and confirm that the recommended safety measures are in place and the said location is safe for work.",
                    "NAME:" => "NAME: ",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME: ",
                    "SIGNATURE:" => "SIGNATURE: "
                ),
                "stage4" => array(
                    "title" => "STAGE 4 – APPROVED */ <del>NOT APPROVED *</del>: BY PROJECT / CONSTRUCTION / STATION / TUNNEL MANAGER",
                    "note" => "I am fully satisfied that reasonably practicable measures have been implemented and enforced. This permit will be revoked immediately & work shall be suspended if any incompatible work observed or reported.",
                    "NAME:" => "NAME: ",
                    "DESIGNATION :" => "DESIGNATION :",
                    "DATE / TIME:" => "DATE / TIME: ",
                    "SIGNATURE:" => "SIGNATURE: "
                ),
                "stage5" => array(
                    "title" => "STAGE 5 – WORK COMPLETION REPORT (Applicant to close and return the permit to STEC WSHE Department)",
                    "The above work was completed on (date)" => "The above work was completed on (date) _____at (Time)______hrs",
                    "NAME:_last" => "NAME: ",
                    "SIGNATURE:_last" => "SIGNATURE: ",
                    "DATE / TIME:_last" => "DATE / TIME: "
                )
            )
        );

        return $list;
    }
}